<?php

namespace Caffeinated\Beverage\Bootstrap;

use Illuminate\Contracts\Foundation\Application;

/**
 * This is the SetApplicationPaths.
 *
 * @package        Caffeinated\Beverage
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class CustomApplicationPaths
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The array of Beverage config items.
     *
     * @var array
     */
    protected $beverage;

    protected $basePath;


    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $this->app = $app;
    }

    /**
     * init
     *
     * @param null $basePath
     * @param null $configPath
     * @return $this
     */
    public function init($basePath = null, $configPath = null)
    {
        if ( $basePath )
        {
            $this->setBasePath($basePath);
        }
        $this->beverage = $this->loadConfig($configPath);

        return $this;
    }

    /**
     * get basePath value
     *
     * @return mixed
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Set the basePath value
     *
     * @param mixed $basePath
     * @return CustomApplicationPaths
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }


    /**
     * loadConfig
     *
     * @param string|null $customConfigPath
     * @return array
     */
    protected function loadConfig($customConfigPath = null)
    {
        if (is_null($customConfigPath)) {
            $customConfigPath = $this->basePath.'/config';
        }

        $beverageConfigPath = realpath(__DIR__.'/../../config');
        $beverageConfigFile = $beverageConfigPath.'/caffeinated.beverage.php';
        $customConfigFile   = $customConfigPath.'/caffeinated.beverage.php';

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
