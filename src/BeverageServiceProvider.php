<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

use Illuminate\View\Engines\CompilerEngine;

/**
 * Caffeinated Beverage Service Provider
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
class BeverageServiceProvider extends ServiceProvider
{
    protected $dir = __DIR__;

    protected $configFiles = [ 'caffeinated.beverage' ];

    protected $provides = [ 'beverage.generator', 'fs' ];

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $app = parent::register();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app    = parent::register();
        $config = $app->make('config');

        $app->singleton('fs', Filesystem::class);
        $this->registerStubs();
        $this->registerHelpers();
    }

    protected function registerHelpers()
    {
        require_once(realpath(__DIR__ . '/helpers.php'));
    }

    protected function registerStubs()
    {
        $app = $this->app;

        /** @var \Illuminate\View\Factory $view */
        $view     = $app->make('view');
        $resolver = $app->make('view.engine.resolver');

        $app->singleton('beverage.generator', StubGenerator::class);

        $resolver->register('stub', function () use ($app) {

            $compiler = $app->make('blade.compiler');

            return new CompilerEngine($compiler);

        });
        $view->addExtension('stub', 'stub');
    }
}
