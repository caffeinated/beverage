<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage\Traits;

/**
 * Multi-Singleton Trait
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
trait MultiSingleton
{
    /**
     * @var array
     */
    private static $instances = array();

    /**
     * Create a new multisingleton instance.
     *
     * @return void
     */
    protected function __construct()
    {
        if (method_exists($this, 'init')) {
            call_user_func_array([$this, 'init'], func_get_args());
        }
    }

    /**
     * Initialize a new instance.
     *
     * @return mixed
     */
    abstract protected function init();

    /**
     * Get the current instance.
     *
     * @param  string  $name
     * @return static
     */
    public static function getInstance($name = 'default')
    {
        if (isset(static::$instances[$name])) {
            return static::$instances[$name];
        }

        $instance = new static();

        static::$instances[$name] = $instance;

        return $instance;
    }
}
