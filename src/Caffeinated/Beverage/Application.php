<?php
namespace Caffeinated\Beverage;

class Application extends \Illuminate\Foundation\Application
{
	/**
	 * The array of Beverage config items.
	 *
	 * @var array
	 */
	protected $path;

	/**
	 * Create a new Caffeinated Beverage application instance.
	 *
	 * @param  string|null  $basePath
	 * @return void
	 */
	public function __construct($basePath = null, $configPath = null)
	{
		if ($basePath) $this->setBasePath($basePath);

		$this->path = $this->loadConfig($configPath);

		parent::__construct($basePath);
	}

	/**
	 * Get the path to the application "app" directory.
	 *
	 * @return string
	 */
	public function path()
	{
		return $this->basePath.'/'.$this->path['app'];
	}

	/**
	 * Get the path to the application configuration files.
	 *
	 * @return string
	 */
	public function configPath()
	{
		return $this->basePath.'/'.$this->path['config'];
	}

	/**
	 * Get the path to the database directory.
	 *
	 * @return string
	 */
	public function databasePath()
	{
		return $this->basePath.'/'.$this->path['database'];
	}

	/**
	 * Get the path to the language files.
	 *
	 * @return string
	 */
	public function langPath()
	{
		return $this->basePath.'/'.$this->path['lang'];
	}

	/**
	 * Get the path to the public / web directory.
	 *
	 * @return string
	 */
	public function publicPath()
	{
		return $this->basePath.'/'.$this->path['public'];
	}

	/**
	 * Get the path to the storage directory.
	 *
	 * @return string
	 */
	public function storagePath()
	{

		return $this->basePath.'/'.$this->path['storage'];
	}



	protected function loadConfig($customConfigPath = null)
	{
		$beverageConfigPath = realpath(__DIR__.'/../../config');
		$beverageConfigFile = $beverageConfigPath.'/paths.php';
		$customConfigFile   = $customConfigPath.'/caffeinated/paths.php';

		$customConfig   = [];
		$beverageConfig = include($beverageConfigFile);

		if (file_exists($customConfigFile)) {
			$customConfig = include($customConfigFile);
		}

		$config = array_replace_recursive($beverageConfig, $customConfig);

		if ($customConfigPath) {
			$config['config'] = str_replace($this->basePath.'/', '', $customConfigPath);
		} else {
			$config['config'] = 'config';
		}

		return $config;
	}
}
