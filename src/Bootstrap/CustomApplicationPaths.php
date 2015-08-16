<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
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
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    protected $paths;

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

        $this->app->setBasePath($this->basePath);

        $this->app->instance('path', $this->getPath());

        foreach ([ 'config', 'database', 'lang', 'public', 'storage' ] as $path) {
            $this->app->instance('path.' . $path, $this->getPath($path));
        }
    }

    protected function getPath($name = null)
    {
        if (is_null($name)) {
            return $this->basePath . DIRECTORY_SEPARATOR . $this->paths[ 'app_path' ];
        } elseif ($name === 'base') {
            return $this->basePath;
        } elseif (array_key_exists($name . '_path', $this->paths)) {
            return $this->basePath . DIRECTORY_SEPARATOR . $this->paths[ $name . '_path' ];
        }
    }

    /**
     * init
     *
     * @param null $basePath
     * @param null $configPath
     * @return $this
     */
    public function loadPaths($basePath = null, $configPath = null)
    {
        if ($basePath) {
            $this->basePath = realpath($basePath);
        }

        $this->paths = $this->loadConfig($configPath);

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
            $customConfigPath = $this->basePath . DIRECTORY_SEPARATOR . 'config';
        }

        $beverageConfigPath = realpath(__DIR__ . '/../../config');
        $beverageConfigFile = $beverageConfigPath . DIRECTORY_SEPARATOR . 'caffeinated.beverage.php';
        $customConfigFile   = realpath($customConfigPath) . DIRECTORY_SEPARATOR . 'caffeinated.beverage.php';

        $customConfig   = [ ];
        $beverageConfig = require($beverageConfigFile);

        if (file_exists($customConfigFile)) {
            $customConfig = require($customConfigFile);
        }

        $config = array_replace_recursive($beverageConfig, $customConfig);

        return $config[ 'custom_paths' ];
    }
}
