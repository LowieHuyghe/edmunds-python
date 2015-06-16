<?php

/**
 * LH Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace LH\Core\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use LH\Core\Exceptions\ConfigNotFoundException;
use LH\Core\Helpers\FileHelper;
use LH\Core\Models\FileEntry;
use LH\Core\Models\User;

/**
 * Controller that handles file upload
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class FileAjaxController extends BaseController
{
	const	SIZE_MAX_PICTURE = 3 * 1024,
			SIZE_MAX_DOCUMENT = 3 * 1024 * 1024;

	/**
	 * List of the accepted methods for routing
	 * @var array
	 */
	public $routeMethods = array(
		'picture' => 0,
		'document' => 0,
		'delete' => 1,
	);

	/**
	 * Upload a picture
	 * @return JsonResponse
	 */
	public function picture()
	{
		$this->validator->required('file');
		$this->validator->image('file');
		$this->validator->size('file', self::SIZE_MAX_PICTURE);

		if ($this->validator->hasErrors())
		{
			$this->response->assignData('success', false);
			$this->response->assignData('errors', $this->validator->getErrors());
			$this->response->setFailed();
		}
		else
		{
			$this->upload();
		}

		return $this->response->returnJson();
	}

	/**
	 * Upload a document
	 * @return JsonResponse
	 */
	public function document()
	{
		$this->validator->required('file');
		$this->validator->mimes('file', array('application/pdf', 'application/msword'));
		$this->validator->size('file', self::SIZE_MAX_DOCUMENT);

		if ($this->validator->hasErrors())
		{
			$this->response->assignData('success', false);
			$this->response->assignData('errors', $this->validator->getErrors());
			$this->response->setFailed();
		}
		else
		{
			$this->upload();
		}

		return $this->response->returnJson();
	}

	/**
	 * Upload the file
	 */
	private function upload()
	{
		$fileEntry = FileHelper::store($this->input->file('file'));
		if ($fileEntry)
		{
			$this->response->assignData('success', true);
			$this->response->assignData('attributes', $fileEntry->getAttributes());
			$this->response->setSuccess();
		}
		else
		{
			$this->response->assignData('success', false);
			$this->response->setFailed();
		}
	}

	/**
	 * Delete the file
	 * @param int $id
	 * @return JsonResponse
	 */
	public function delete($id)
	{
		//Delete the file
		if (FileHelper::delete($id))
		{
			$this->response->assignData('success', true);
			$this->response->setSuccess();
		}
		else
		{
			$this->response->assignData('success', false);
			$this->response->setFailed();
		}

		return $this->response->returnJson();
	}

}