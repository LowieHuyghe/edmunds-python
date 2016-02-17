<?php

/**
 * Core
 *
 * The core of any web-project by Lowie Huyghe
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */

namespace Core\Filesystem\Controllers;

use Core\Bases\Http\Controllers\BaseController;
use Core\Http\Response;
use Illuminate\Http\JsonResponse;
use Core\Filesystem\Models\FileEntry;

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
	 * Upload a picture
	 * @return JsonResponse
	 */
	public function postPicture()
	{
		$this->validator->value('file')->required()->mimes(array('gif', 'jpeg', 'jpg', 'png'))->max(self::SIZE_MAX_PICTURE);

		//Set response type to json
		$this->response->setType(Response::TYPE_JSON);

		if ($this->validator->hasErrors())
		{
			$this->response->assign('errors', $this->validator->getErrors()->errors()->all());
			return false;
		}
		else
		{
			return $this->upload();
		}
	}

	/**
	 * Upload a document
	 * @return JsonResponse
	 */
	public function postDocument()
	{
		$this->validator->value('file')->required()->mimes(array('pdf', 'doc', 'docx'))->max(self::SIZE_MAX_DOCUMENT);

		//Set response type to json
		$this->response->setType(Response::TYPE_JSON);

		if ($this->validator->hasErrors())
		{
			$this->response->assign('errors', $this->validator->getErrors()->errors()->all());
			return false;
		}
		else
		{
			return $this->upload();
		}
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
			$this->response->download($fileEntry->getPath(), $fileEntry->original_name);
			return true;
		}
		else
		{
			//Set response type to json
			$this->response->setType(Response::TYPE_JSON);
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

		//Set response type to json
		$this->response->setType(Response::TYPE_JSON);

		//Delete the file
		return ($fileEntry && $fileEntry->delete());
	}

}