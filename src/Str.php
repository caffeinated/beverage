<?php
namespace Caffeinated\Beverage;

use Caffeinated\Beverage\Stringy\Stringy;

/**
 * String helper methods
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
class Str
{
	/**
	 * Get the instance of Stringy.
	 *
	 * @param  array  $arguments
	 * @return Stringy
	 */
	public function getStringyString($arguments)
	{
		$str = head($arguments);

		return Stringy::create($str);
	}

	/**
	 * Create a new PHP Underscore string instance.
	 *
	 * @param  string  $string
	 * @return static
	 */
	public static function from($string)
	{
		return \Underscore\Types\String::from($string);
	}

	/**
	 * Create a new Stringy string instance.
	 *
	 * @param  string  $string
	 * @return \Caffeinated\Beverage\Stringy\Stringy
	 */
	public static function create($string)
	{
		return Stringy::create($string);
	}

	/**
	 * Magic call method.
	 *
	 * @param  string  $name
	 * @param  mixed   $parameters
	 * @return mixed
	 */
	public function __call($name, $parameters)
	{
		if (method_exists('Underscore\Methods\StringMethods', $name)) {
			return forward_static_call_array(['Underscore\Types\String', $name], $parameters);
		} else {
			$object = $this->getStringyString($parameters);

			if (method_exists($object, $name)) {
				return call_user_func_array([$object, $name], array_slice($parameters, 1));
			}
		}
	}

	/**
	 * Magic call static method.
	 *
	 * @param  string  $name
	 * @param  mixed   $parameters
	 * @return mixed
	 */
	public static function __callStatic($name, $parameters)
	{
		return call_user_func_array([new static(), $name], $parameters);
	}
}