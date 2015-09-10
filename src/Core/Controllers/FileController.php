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
	public static $routeMethods = array(
		'/' => array('p' => array('\d+')),
		'picture' => array('m' => array('post')),
		'document' => array('m' => array('post')),
		'delete' => array('m' => array('post'), 'p' => array('\d+')),
	);

	/**
	 * Upload a picture
	 * @return JsonResponse
	 */
	public function postPicture()
	{
		$this->validator->required('file');
		$this->validator->mimes('file', array('gif', 'jpeg', 'jpg', 'png'));
		$this->validator->max('file', self::SIZE_MAX_PICTURE);

		if ($this->validator->hasErrors())
		{
			$this->response->assign('errors', $this->validator->getErrors()->errors()->all());
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
		$this->validator->mimes('file', array('pdf', 'doc', 'docx'));
		$this->validator->max('file', self::SIZE_MAX_DOCUMENT);

		if ($this->validator->hasErrors())
		{
			$this->response->assign('errors', $this->validator->getErrors()->errors()->all());
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

		if ($fileEntry && $fileEntry->save())
		{
			$this->response->assign('attributes', array_merge(array('id' => $fileEntry->id), $fileEntry->getAttributes()));
			$this->response->setSuccess();
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
	public function postDelete($id)
	{
		$fileEntry = FileEntry::find($id);

		//Delete the file
		if ($fileEntry && $fileEntry->delete())
		{
			$this->response->setSuccess();
		}
		else
		{
			$this->response->setFailed();
		}

		return $this->response->responseJson();
	}

}