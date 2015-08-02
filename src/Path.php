<?php
namespace Caffeinated\Beverage;

use Webmozart\PathUtil\Path as BasePath;

/**
 * Path
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
class Path extend BasePath
{
	/**
	 * Joins a split file system path.
	 *
	 * @param  array|string  $path
	 * @return string
	 */
	public static function join()
	{
		$arguments = func_get_args();

		if (func_get_args() === 1 and is_array($arguments[0])) {
			$arguments = $arguments[0];
		}

		foreach ($arguments as $key => $argument) {
			$arguments[$key] = String::removeRight($arguments[$key], '/');

			if ($key > 0) {
				$arguments[$key] = String::removeLeft($arguments[$key], '/');
			}
		}

		return implode(DIRECTORY_SEPARATOR, $arguments);
	}
}