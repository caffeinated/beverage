<?php
/**
 * Caffeinated Beverage helper methods
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */

use Caffeinated\Beverage\Path;

if (! function_exists('path_join')) {
	function path_join()
	{
		return forward_static_call_array(['Caffeinated\Beverage\Path', 'join'], func_get_args());
	}
}

if (! function_exists('path_is_absolute')) {
	function path_is_absolute()
	{
		return forward_static_call_array(['Caffeinated\Beverage\Path', 'isAbsolute'], func_get_args());
	}
}

if (! function_exists('path_is_relative')) {
	function path_is_relative()
	{
		return forward_static_call_array(['Caffeinated\Beverage\Path', 'isRelative'], func_get_args());
	}
}

if (! function_exists('path_get_directory')) {
	function path_get_directory()
	{
		return forward_static_call_array(['Caffeinated\Beverage\Path', 'getDirectory'], func_get_args());
	}
}

if (! function_exists('path_get_extension')) {
	function path_get_extension()
	{
		return forward_static_call_array(['Caffeinated\Beverage\Path', 'getExtension'], func_get_args());
	}
}

if (! function_exists('path_get_filename')) {
	function path_get_filename()
	{
		return forward_static_call_array(['Caffeinated\Beverage\Path', 'getFilename'], func_get_args());
	}
}