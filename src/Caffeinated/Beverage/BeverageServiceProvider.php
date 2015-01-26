<?php
namespace Caffeinated\Beverage;

use Illuminate\Support\ServiceProvider;

class BeverageServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Array of packages config files.
	 *
	 * @var array
	 */
	protected $configFiles = [
		'paths'
	];

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerResources();
	}

	/**
	 * Register the package resources.
	 *
	 * @return void
	 */
	protected function registerResources()
	{
		foreach ($this->configFiles as $configFile) {
			$userConfigFile    = app()->configPath().'/caffeinated/'.$configFile.'.php';
			$packageConfigFile = __DIR__.'/../../config/'.$configFile.'.php';
			$config            = $this->app['files']->getRequire($packageConfigFile);

			if (file_exists($userConfigFile)) {
				$userConfig = $this->app['files']->getRequire($userConfigFile);
				$config     = array_replace_recursive($config, $userConfig);
			}

			$this->app['config']->set('caffeinated::'.$configFile, $config);
		}		
	}
}
