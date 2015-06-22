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

namespace LH\Core\Models;

/**
 * The model for files
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 *
 * @property int $id Database table-id
 * @property string name
 * @property string md5
 * @property string sha1
 * @property string original_name
 * @property string mime
 * @property int size
 */
class FileEntry extends BaseModel
{
	/**
	 * The table to store the data
	 * @var string
	 */
	protected $table = 'file_entries';

	/**
	 * Timestamps in the table
	 * @var bool|array
	 */
	public $timestamps = true;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		$this->addValidationRules();
	}

	/**
	 * Add the validation of the model
	 */
	public function addValidationRules()
	{
		$this->validator->integer('id');

		$this->validator->required('name');
		$this->validator->max('name', 20);
		$this->validator->unique('name', $this->table);

		$this->validator->required('md5');
		$this->validator->max('md5', 32);

		$this->validator->required('sha1');
		$this->validator->max('sha1', 40);

		$this->validator->required('original_name');
		$this->validator->max('original_name', 255);

		$this->validator->required('mime');
		$this->validator->max('mime', 20);

		$this->validator->integer('size');
		$this->validator->required('size');

		$this->validator->date('created_at');
		$this->validator->date('updated_at');
	}

}
