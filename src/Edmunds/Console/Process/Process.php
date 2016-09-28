<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Console\Process;

use Edmunds\Console\Process\Concerns\ArtisanProcess;
use Edmunds\Console\Process\SymfonyProcess;
use Exception;
use Illuminate\Console\Application as Artisan;
use StdClass;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\RuntimeException;
use Throwable;

/**
 * The process for the kernel
 */
class Process extends SymfonyProcess
{
	use ArtisanProcess;

	/**
	 * Constructor.
	 *
	 * @param string         $commandline The command line to run
	 * @param string|null    $cwd         The working directory or null to use the working dir of the current PHP process
	 * @param array|null     $env         The environment variables or null to use the same environment as the current PHP process
	 * @param string|null    $input       The input
	 * @param int|float|null $timeout     The timeout in seconds or null to disable
	 * @param array          $options     An array of options for proc_open
	 *
	 * @throws RuntimeException When proc_open is not installed
	 */
	public function __construct($commandline, $cwd = null, array $env = null, $input = null, $timeout = 60, array $options = array())
	{
		// Google App Engine
		if (app()->isGae())
		{
			$this->commandline = $commandline;
			$this->cwd = $cwd;

			if (null !== $env)
			{
				$this->setEnv($env);
			}

			$this->setInput($input);
			$this->setTimeout($timeout);
			$this->useFileHandles = '\\' === DIRECTORY_SEPARATOR;
			$this->pty = false;
			$this->enhanceWindowsCompatibility = true;
			$this->enhanceSigchildCompatibility = '\\' !== DIRECTORY_SEPARATOR && $this->isSigchildEnabled();
			$this->options = array_replace(array('suppress_errors' => true, 'binary_pipes' => true), $options);
		}
		else
		{
			parent::__construct($commandline, $cwd, $env, $input, $timeout, $options);
		}
	}

	/**
	 * Updates the status of the process, reads pipes.
	 *
	 * @param bool $blocking Whether to use a blocking read call
	 */
	protected function updateStatus($blocking)
	{
		// Google App Engine
		if (app()->isGae())
		{
			if (self::STATUS_STARTED !== $this->status)
			{
				return;
			}

			$this->processInformation = $this->procGetStatus($this->process);
			$running = $this->processInformation['running'];

			$this->readPipes($running && $blocking, '\\' !== DIRECTORY_SEPARATOR || !$running);

			if ($this->fallbackStatus && $this->enhanceSigchildCompatibility && $this->isSigchildEnabled())
			{
				$this->processInformation = $this->fallbackStatus + $this->processInformation;
			}

			if ( ! $running)
			{
				$this->close();
			}
		}
		else
		{
			return parent::updateStatus($blocking);
		}
	}

	/**
	 * Starts the process and returns after writing the input to STDIN.
	 *
	 * This method blocks until all STDIN data is sent to the process then it
	 * returns while the process runs in the background.
	 *
	 * The termination of the process can be awaited with wait().
	 *
	 * The callback receives the type of output (out or err) and some bytes from
	 * the output in real-time while writing the standard input to the process.
	 * It allows to have feedback from the independent process during execution.
	 *
	 * @param callable|null $callback A PHP callback to run whenever there is some
	 *                                output available on STDOUT or STDERR
	 *
	 * @throws RuntimeException When process can't be launched
	 * @throws RuntimeException When process is already running
	 * @throws LogicException   In case a callback is provided and output has been disabled
	 */
	public function start(callable $callback = null)
	{
		// Google App Engine
		if (app()->isGae())
		{
			if ($this->isRunning())
			{
				throw new RuntimeException('Process is already running');
			}
			if ($this->outputDisabled && null !== $callback)
			{
				throw new LogicException('Output has been disabled, enable it to allow the use of a callback.');
			}

			$this->resetProcessData();
			$this->starttime = $this->lastOutputTime = microtime(true);
			$this->callback = $this->buildCallback($callback);
			$descriptors = $this->getDescriptors();

			$commandline = $this->commandline;

			if ('\\' === DIRECTORY_SEPARATOR && $this->enhanceWindowsCompatibility)
			{
				$commandline = 'cmd /V:ON /E:ON /D /C "('.$commandline.')';
				foreach ($this->processPipes->getFiles() as $offset => $filename)
				{
					$commandline .= ' ' . $offset . '>' . $filename;
				}
				$commandline .= '"';

				if (!isset($this->options['bypass_shell'])) {
					$this->options['bypass_shell'] = true;
				}
			}
			elseif (!$this->useFileHandles && $this->enhanceSigchildCompatibility && $this->isSigchildEnabled())
			{
				// last exit code is output on the fourth pipe and caught to work around --enable-sigchild
				$descriptors[3] = array('pipe', 'w');

				// See https://unix.stackexchange.com/questions/71205/background-process-pipe-input
				$commandline = '{ ('.$this->commandline.') <&3 3<&- 3>/dev/null & } 3<&0;';
				$commandline .= 'pid=$!; echo $pid >&3; wait $pid; code=$?; echo $code >&3; exit $code';

				// Workaround for the bug, when PTS functionality is enabled.
				// @see : https://bugs.php.net/69442
				$ptsWorkaround = fopen(__FILE__, 'r');
			}

			$this->process = $this->procOpen($commandline, $descriptors, $this->processPipes->pipes, $this->cwd, $this->env, $this->options);

			if (empty($this->process))
			{
				throw new RuntimeException('Unable to launch a new process.');
			}
			$this->status = self::STATUS_STARTED;

			if (isset($descriptors[3]))
			{
				$this->fallbackStatus['pid'] = (int) fgets($this->processPipes->pipes[3]);
			}

			if ($this->tty)
			{
				return;
			}

			$this->updateStatus(false);
			$this->checkTimeout();

			$this->handleProcess($this->process);
		}
		else
		{
			return parent::start($callback);
		}
	}

	/**
	 * Waits for the process to terminate.
	 *
	 * The callback receives the type of output (out or err) and some bytes
	 * from the output in real-time while writing the standard input to the process.
	 * It allows to have feedback from the independent process during execution.
	 *
	 * @param callable|null $callback A valid PHP callback
	 *
	 * @return int The exitcode of the process
	 *
	 * @throws RuntimeException When process timed out
	 * @throws RuntimeException When process stopped after receiving signal
	 * @throws LogicException   When process is not yet started
	 */
	public function wait(callable $callback = null)
	{
		// Google App Engine
		if (app()->isGae())
		{
			$this->requireProcessIsStarted(__FUNCTION__);

			$this->updateStatus(false);
			if (null !== $callback)
			{
				$this->callback = $this->buildCallback($callback);
			}

			do
			{
				$this->checkTimeout();
				$running = '\\' === DIRECTORY_SEPARATOR ? $this->isRunning() : $this->processPipes->areOpen();
				$this->readPipes($running, '\\' !== DIRECTORY_SEPARATOR || !$running);
			} while ($running);

			while ($this->isRunning())
			{
				usleep(1000);
			}

			if ($this->processInformation['signaled'] && $this->processInformation['termsig'] !== $this->latestSignal)
			{
				throw new RuntimeException(sprintf('The process has been signaled with signal "%s".', $this->processInformation['termsig']));
			}

			return $this->exitcode;
		}

		return parent::wait($callback);
	}

	/**
	 * Closes process resource, closes file handles, sets the exitcode.
	 *
	 * @return int The exitcode
	 */
	protected function close()
	{
		// Google App Engine
		if (app()->isGae())
		{
			$this->processPipes->close();
			if ( ! empty($this->process))
			{
				$this->procClose($this->process);
			}
			$this->exitcode = $this->processInformation['exitcode'];
			$this->status = self::STATUS_TERMINATED;

			if (-1 === $this->exitcode)
			{
				if ($this->processInformation['signaled'] && 0 < $this->processInformation['termsig'])
				{
					// if process has been signaled, no exitcode but a valid termsig, apply Unix convention
					$this->exitcode = 128 + $this->processInformation['termsig'];
				}
				elseif ($this->enhanceSigchildCompatibility && $this->isSigchildEnabled())
				{
					$this->processInformation['signaled'] = true;
					$this->processInformation['termsig'] = -1;
				}
			}

			// Free memory from self-reference callback created by buildCallback
			// Doing so in other contexts like __destruct or by garbage collector is ineffective
			// Now pipes are closed, so the callback is no longer necessary
			$this->callback = null;

			return $this->exitcode;
		}

		return parent::wait($callback);
	}

	/**
	 * Get the generated proc_get_status when proc is not enabled
	 * @param  mixed $process
	 * @return array
	 */
	protected function procGetStatus($process)
	{
		return array(
			'command' => $process->commandline,
			'pid' => 0,
			'running' => $process->running,
			'signaled' => false,
			'stopped' => $process->stopped,
			'exitcode' => $process->exitcode,
			'termsig' => 0,
			'stopsig' => 0,
		);
	}

	/**
	 * 'Open' a fake process
	 * @param  string $commandline
	 * @param  array $descriptors
	 * @param  array $pipes
	 * @param  string $cwd
	 * @param  string $env
	 * @param  array $options
	 * @return mixed
	 */
	protected function procOpen($commandline, $descriptors, $pipes, $cwd, $env, $options)
	{
		$process = new StdClass();

		$process->commandline = $commandline;
		$process->descriptors = $descriptors;
		$process->pipes = $pipes;
		$process->cwd = $cwd;
		$process->env = $env;
		$process->options = $options;

		$process->running = true;
		$process->stopped = false;
		$process->exitcode = 0;

		return $process;
	}

	/**
	 * Get the generated proc_get_status when proc is not enabled
	 * @param  mixed $process
	 * @return array
	 */
	protected function procClose($process)
	{
		$process->running = false;
		$process->stopped = true;
		$process->exitcode = 1;
	}

	/**
	 * Handle the given process
	 * @param  StdClass $process
	 * @return void
	 */
	protected function handleProcess($process)
	{
		// ARTISAN
		$artisan = defined('ARTISAN_BINARY') ? ARTISAN_BINARY : 'artisan';
		if (starts_with($process->commandline, $artisan))
		{
			$artisanCommand = trim(substr($process->commandline, strlen($artisan)));

			$process->exitcode = $this->handleArtisanCommand($artisanCommand);
		}
		else
		{
			throw new Exception('Could not handle process');
		}

		$process->running = false;
	}
}