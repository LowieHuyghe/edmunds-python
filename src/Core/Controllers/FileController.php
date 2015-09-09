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

use Illuminate\Http\JsonResponse;
use LH\Core\Helpers\FileHelper;
use LH\Core\Models\FileEntry;

/**
 * Controller that handles file upload
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class FileController extends BaseController
{
	const	SIZE_MAX_PICTURE = 3 * 1024,
			SIZE_MAX_DOCUMENT = 3 * 1024 * 1024;

	/**
	 * List of the accepted methods for routing
	 * @var array
	 */
	public $routeMethods = array(
		'postPicture' => 0,
		'postDocument' => 0,
		'get' => 1,
		'delete' => 1,
	);

	/**
	 * Upload a picture
	 * @return JsonResponse
	 */
	public function postPicture()
	{
		$this->validator->required('file');
		$this->validator->image('file');
		$this->validator->max('file', self::SIZE_MAX_PICTURE);

		if ($this->validator->hasErrors())
		{
			$this->response->assign('errors', $this->validator->getErrors());
			$this->response->setFailed();
		}
		else
		{
			$this->upload();
		}

		return $this->response->responseJson();
	}

	/**
	 * Upload a document
	 * @return JsonResponse
	 */
	public function postDocument()
	{
		$this->validator->required('file');
		$this->validator->mimes('file', array('application/pdf', 'application/msword'));
		$this->validator->size('file', self::SIZE_MAX_DOCUMENT);

		if ($this->validator->hasErrors())
		{
			$this->response->assign('errors', $this->validator->getErrors());
			$this->response->setFailed();
		}
		else
		{
			$this->upload();
		}

		return $this->response->responseJson();
	}

	/**
	 * Upload the file
	 */
	private function upload()
	{
		$fileEntry = FileEntry::generateFromInput('file');

		if ($fileEntry)
		{
			$this->response->assign('attributes', $fileEntry->getAttributes());
		}
		else
		{
			$this->response->setFailed();
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
			return $this->response->responseDownload($fileEntry->getPath(), $fileEntry->original_name);
		}
		else
		{
			$this->response->setFailed();
			return $this->response->responseJson();
		}
	}

	/**
	 * Delete the file
	 * @param int $id
	 * @return JsonResponse
	 */
	public function delete($id)
	{
		$fileEntry = FileEntry::find($id);

		//Delete the file
		if (!$fileEntry->delete())
		{
			$this->response->setFailed();
		}

		return $this->response->responseJson();
	}

}