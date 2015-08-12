<?php
namespace Caffeinated\Beverage;



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

    protected $configFiles = ['caffeinated.beverage'];

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
        $app = parent::register();

        $app->singleton('fs', Filesystem::class);

        require_once(__DIR__.'/helpers.php');
    }
}
