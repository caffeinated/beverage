<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

use ErrorException;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * This is the ConsoleServiceProvider.
 *
 * @package        Caffeinated\Beverage
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
abstract class ConsoleServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The namespace where the commands are
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * Register the service provider.
     *
     * @throws ErrorException
     */
    public function register()
    {
        $errorMsg = "Your ConsoleServiceProvider(AbstractConsoleProvider) requires property";
        if (! isset($this->namespace) or ! is_string($this->namespace)) {
            throw new ErrorException("$errorMsg \$namespace to be an string");
        }
        if (! isset($this->commands) or ! is_array($this->commands)) {
            throw new ErrorException("$errorMsg \$commands to be an array");
        }

        $bindings = [ ];
        foreach ($this->commands as $binding => $command) {
            $binding    = $this->prefix . $binding;
            $bindings[] = $binding;
            $this->{"registerCommand"}($binding, $command);
        }

        $this->commands($bindings);
    }

    /**
     * Register the command.
     *
     * @param $command
     * @param $binding
     * @throws ErrorException
     */
    protected function registerCommand($binding, $command)
    {
        $class = $this->namespace . '\\' . $command . 'Command';
        if (! class_exists($class)) {
            throw new ErrorException("Your ConsoleServiceProvider(AbstractConsoleProvider)->registerCommand($command, $binding) could not find $class");
        }
        $this->app->singleton($binding, $class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_values($this->commands);
    }
}
