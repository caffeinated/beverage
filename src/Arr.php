<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

/**
 * Array helper methods
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 * @mixin \Underscore\Methods\ArraysMethods
 * @mixin \Illuminate\Support\Arr
 */
class Arr
{
    /**
     * Unflattens a single stacked array back into a multidimensional array.
     *
     * @param  array  $array
     * @param  string $delimiter
     * @return array
     */
    public static function unflatten(array $array, $delimiter = '.')
    {
        $unflattenedArray = array();

        foreach ($array as $key => $value) {
            $keyList  = explode($delimiter, $key);
            $firstKey = array_shift($keyList);

            if (sizeof($keyList) > 0) {
                $subArray = static::unflatten(array( implode($delimiter, $keyList) => $value ), $delimiter);

                foreach ($subArray as $subArrayKey => $subArrayValue) {
                    $unflattenedArray[ $firstKey ][ $subArrayKey ] = $subArrayValue;
                }
            } else {
                $unflattenedArray[ $firstKey ] = $value;
            }
        }

        return $unflattenedArray;
    }

    /**
     * Get a value from the passed array, and remove it.
     *
     * @param  array  $array
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function pull(&$array, $key, $default = null)
    {
        $value = static::get($array, $key, $default);

        static::forget($array, $key);

        return $value;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is provided, the entire array will be replaced.
     *
     * @param  array  $array
     * @param  string $key
     * @param  mixed  $value
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[ $key ]) or ! is_array($array[ $key ])) {
                $array[ $key ] = [ ];
            }

            $array =& $array[ $key ];
        }

        $array[ array_shift($keys) ] = $value;

        return $array;
    }

    /**
     * Remove one or more array key items from the given array using "dot" notation.
     *
     * @param  array  $array
     * @param  string $keys
     * @return void
     */
    public static function forget(&$array, $keys)
    {
        $original =& $array;

        foreach ((array)$keys as $key) {
            $parts = explode('.', $key);

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[ $part ]) and is_array($array[ $part ])) {
                    $array =& $array[ $part ];
                }

                unset($array[ array_shift($parts) ]);

                $array =& $original;
            }
        }
    }

    public static function from($arr = [])
    {
        return \Underscore\Types\Arrays::from($arr);
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([ new static(), $name ], $arguments);
    }

    public function __call($name, $arguments)
    {
        if (method_exists('Underscore\Methods\ArraysMethods', $name)) {
            return forward_static_call_array([ 'Underscore\Types\Arrays', $name ], $arguments);
        } elseif (method_exists('Illuminate\Support\Arr', $name)) {
            return forward_static_call_array([ 'Illuminate\Support\Arr', $name ], $arguments);
        }
    }
}
