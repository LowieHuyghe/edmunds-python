<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Gae\Foundation\Controllers;

use Edmunds\Bases\Http\Controllers\BaseController;
use Edmunds\Localization\Format\DateTime;

/**
 * Queue handler controller for Google App Engine
 */
class QueueHandlerController extends BaseController
{
	/**
	 * The name of the queue
	 * @var string
	 */
	protected $queueName;

	/**
	 * Name of the current task
	 * @var string
	 */
	protected $taskName;

	/**
	 * The retry count of the task
	 * @var int
	 */
	protected $taskRetryCount;

	/**
	 * The number of times this task has previously failed during the execution phase.
	 * @var int
	 */
	protected $taskExecutionCount;

	/**
	 * The target execution time of the task
	 * @var DateTime
	 */
	protected $taskETA;

	/**
	 * Indicates that a task running on a manual or basic scaled module fails immediately instead of waiting in a pending queue.
	 * @var bool
	 */
	protected $failFast;

	/**
	 * Function called after construct
	 */
	public function initialize()
	{
		$this->queueName = $this->request->getHeader('X-AppEngine-QueueName');
		if ( ! $this->app->runninginGaeConsole() || ! $this->queueName)
		{
			abort(403);
		}

		$this->taskName = $this->request->getHeader('X-AppEngine-TaskName');
		$this->taskRetryCount = $this->request->getHeader('X-AppEngine-TaskRetryCount');
		$this->taskExecutionCount = $this->request->getHeader('X-AppEngine-TaskExecutionCount');
		$this->taskETA = new DateTime(date(DateTime::DEFAULT_TO_STRING_FORMAT, $this->request->getHeader('X-AppEngine-TaskETA')));
		$this->failFast = $this->request->getHeader('X-AppEngine-FailFast');
	}

	/**
	 * Run Task
	 */
	public function runTask()
	{
		// fetch worker instance
		$worker = $this->app->make('queue.worker');
		$worker->setDaemonExceptionHandler($this->app->make('Illuminate\Contracts\Debug\ExceptionHandler'));

		// process the job
		// NOTE: the number of retries can be set here (which has priority)
		// 		number of retries can also be set in Edmunds\Queue\QueueJob
		$result = $worker->pop(null, $this->queueName, 0, 3, 0);
	}
}
