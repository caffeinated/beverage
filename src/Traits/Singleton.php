<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage\Traits;

/**
 * Singleton Trait
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
trait Singleton
{
    /**
     * @var mixed
     */
    private static $instance;

    /**
     * Create a new singleton instance.
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
        if (isset(static::$instance)) {
            return static::$instance;
        }

        $instance = new static();

        static::$instance = $instance;

        return $instance;
    }
}
