<?php
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

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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

        if ( array_key_exists('stub', $view->getExtensions()) )
        {
            return;
        }

        $app->singleton('stub.generator', StubGenerator::class);

        $resolver->register('stub', function () use ($app)
        {
            $compiler = $app->make('blade.compiler');

            return new CompilerEngine($compiler);

        });
        $view->addExtension('stub', 'stub');
    }
}
