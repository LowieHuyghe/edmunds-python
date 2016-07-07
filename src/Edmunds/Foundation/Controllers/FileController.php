<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Foundation\Controllers;

use Edmunds\Application;
use Edmunds\Bases\Http\Controllers\BaseController;
use Edmunds\Filesystem\Models\FileEntry;
use Edmunds\Filesystem\Models\FileType;
use Edmunds\Http\Response;
use Illuminate\Http\JsonResponse;

/**
 * Controller that handles file upload
 */
class FileController extends BaseController
{
	const	SIZE_MAX_FILE = 3 * 1024 * 1024;

	/**
	 * Register the default routes for this controller
	 * @param  Application $app
	 * @param  string $prefix
	 * @param  array  $middleware
	 */
	public static function registerRoutes(&$app, $prefix ='file/', $middleware = array())
	{
		// fetch file
		$app->get($prefix . '{id}', array(
			'uses' => get_called_class() . '@get',
			'middleware' => $middleware,
		));

		// upload methods
		$app->post($prefix . 'upload', array(
			'uses' => get_called_class() . '@postUpload',
			'middleware' => $middleware,
		));

		// delete file
		$app->post($prefix . '{id}/delete', array(
			'uses' => get_called_class() . '@postDelete',
			'middleware' => $middleware,
		));
	}

	/**
	 * The default output type of the response, only used when set
	 * @var int
	 */
	protected $outputType = Response::TYPE_JSON;

	/**
	 * Upload a file
	 * @return JsonResponse
	 */
	public function postUpload()
	{
		$this->input->rule('type')->required()->integer();

		// check if file and type are present
		if ($this->input->hasErrors())
		{
			$this->response->errors($this->input->getErrors());
			return false;
		}

		// type and file are present, check it
		else
		{
			$this->input->rule('file')->mimes(FileType::getExtensionsForType($this->input->get('type')))->max(self::SIZE_MAX_FILE);

			// check if file mime is correct
			if ($this->input->hasErrors())
			{
				$this->response->errors($this->input->getErrors());
				return false;
			}

			// upload it
			else
			{
				$fileEntry = FileEntry::generateFromInput($this->input->get('file'));

				return $this->upload($fileEntry);
			}
		}
	}

	/**
	 * Upload the file
	 * @param FileEntry $fileEntry
	 */
	protected function upload($fileEntry)
	{
		if ($fileEntry && $fileEntry->save())
		{
			$this->response->assign('attributes', array_merge(array('id' => $fileEntry->id), $fileEntry->getAttributes()));
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Download the file
	 * @param int $id
	 * @return JsonResponse
	 */
	public function get($id)
	{
		//Get the file
		$fileEntry = FileEntry::find($id);

		if ($fileEntry)
		{
			//Set response type to download
			$this->response
				->header('Content-type', $fileEntry->mime)
				->header('Content-length', $fileEntry->size);

			switch($fileEntry->type)
			{
				case FileType::IMAGE:
				case FileType::AUDIO:
				case FileType::DOCUMENT:
				case FileType::VIDEO:
					$response->content(file_get_contents($fileEntry->getPath()));
					break;
				default:
					$response->download($fileEntry->getPath(), $fileEntry->original_name);
					break;
			}
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Delete the file
	 * @param int $id
	 * @return JsonResponse
	 */
	public function postDelete($id)
	{
		$fileEntry = FileEntry::find($id);

		//Delete the file
		return ($fileEntry && $fileEntry->delete());
	}

}