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

namespace Core\Bases\Analytics\Tracking;

use Core\Bases\Structures\BaseStructure;
use Core\Http\Client\Visitor;
use Core\Http\Request;
use Core\Io\Validation\Validation;
use Core\Registry\Queue;
use Core\Registry\Registry;

/**
 * The structure for log values
 *
 * @author		Lowie Huyghe <iam@lowiehuyghe.com>
 * @copyright	Copyright (C) 2015, Lowie Huyghe. All rights reserved. Unauthorized copying of this file, via any medium is strictly prohibited. Proprietary and confidential.
 * @license		http://LicenseUrl
 * @since		Version 0.1
 */
class BaseLogValue extends BaseStructure
{
	/**
	 * The mapping of the parameters for the call
	 * @var array
	 */
	protected $parameterMapping = array();

	/**
	 * Get the attributes mapped
	 * @return array
	 */
	public function getAttributesMapped()
	{
		$data = array();

		foreach ($this->attributes as $parameter => $value)
		{
			if (!isset($this->parameterMapping[$parameter]))
			{
				//throw new \Exception("There is no mapping for the parameter: $parameter");
				continue;
			}

			//Bool needs to be 1/0
			if (is_bool($value))
			{
				$value = $value ? 1 : 0;
			}

			//Some parameter-names need to be filled in
			$parameterName = $this->parameterMapping[$parameter];

			if ($value instanceof BaseLogValue)
			{
				//Add query-item
				$data[$parameterName] = json_encode($value->getAttributesMapped());
			}
			elseif (is_array($value))
			{
				if (strpos($this->parameterMapping[$parameter], '{0}'))
				{
					foreach ($value as $customValue)
					{
						//Some parameter-names need to be filled in
						$parameterName = $this->parameterMapping[$parameter];

						for ($i=0 ; $i < count($customValue)-1 ; ++$i)
						{
							$parameterName = str_replace('{' . $i . '}', $customValue[$i], $parameterName);
						}
						$customValue = last($customValue);

						//Add query-item
						$data[$parameterName] = $customValue;
					}
				}
				else
				{
					//Add query-item
					$data[$parameterName] = json_encode($value);
				}
			}
			else
			{
				//Add query-item
				$data[$parameterName] = $value;
			}
		}

		return $data;
	}

}
