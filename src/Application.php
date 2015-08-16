<?php
/**
* Part of the Caffeinated PHP packages.
*
* MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

/**
 * Caffeinated Beverage Application
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
class Application extends \Illuminate\Foundation\Application
{
    /**
     * The array of Beverage config items.
     *
     * @var array
     */
    protected $beverage;

    /**
     * Create a new Caffeinated Beverage application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = null, $configPath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->beverage = $this->loadConfig($configPath);

        parent::__construct($basePath);
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @return string
     */
    public function path()
    {
        return $this->basePath.'/'.$this->beverage['app_path'];
    }

    /**
     * Get the path to the application configuration files.
     *
     * @return string
     */
    public function configPath()
    {
        return $this->basePath.'/'.$this->beverage['config_path'];
    }

    /**
     * Get the path to the database directory.
     *
     * @return string
     */
    public function databasePath()
    {
        return $this->basePath.'/'.$this->beverage['database_path'];
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath()
    {
        return $this->basePath.'/'.$this->beverage['lang_path'];
    }

    /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function publicPath()
    {
        return $this->basePath.'/'.$this->beverage['public_path'];
    }

    /**
     * Get the path to the storage directory.
     *
     * @return string
     */
    public function storagePath()
    {

        return $this->basePath.'/'.$this->beverage['storage_path'];
    }

    /**
     * Manually load our beverage config file. We need to do this since this
     * file is loaded before the config service provider is kicked in.
     *
     * @return array
     */
    protected function loadConfig($customConfigPath = null)
    {
        if (is_null($customConfigPath)) {
            $customConfigPath = $this->basePath.'/config';
        }

        $beverageConfigPath = realpath(__DIR__.'/../config');
        $beverageConfigFile = $beverageConfigPath.'/beverage.php';
        $customConfigFile   = $customConfigPath.'/beverage.php';

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
