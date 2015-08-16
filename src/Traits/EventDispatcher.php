<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage\Traits;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;

/**
 * Event Dispatcher Trait
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
trait EventDispatcher
{
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $dispatcher;

    /**
     * Set the active event dispatcher.
     *
     * @param  Dispatcher  $dispatcher
     * @return void
     */
    public static function setDispatcher(Dispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * Get the active event dispatcher.
     *
     * @return Dispatcher
     */
    public static function getDispatcher()
    {
        return static::$dispatcher;
    }

    /**
     * Register an event for the dispatcher to listen for.
     *
     * @param  string   $name
     * @param  Closure  $callback
     * @return void
     */
    protected function registerEvent($name, Closure $callback)
    {
        if (! isset(static::$dispatcher)) {
            $this->initEventDispatcher();
        }

        static::$dispatcher->listen($name, $callback);
    }

    /**
     * Fire off an event.
     *
     * @param  string  $name
     * @param  mixed   $payload
     * @return mixed
     */
    protected function fireEvent($name, $payload = null)
    {
        if (! isset(static::$dispatcher)) {
            $this->initEventDispatcher();
        }

        static::$dispatcher->fire($name, $payload);
    }

    /**
     * Initialize a new Event Dispatcher instance.
     *
     * @return void
     */
    protected function initEventDispatcher()
    {
        static::setDispatcher(app('events'));
    }
}
