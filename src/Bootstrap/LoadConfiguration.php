<?php

namespace Caffeinated\Beverage\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration as BaseLoadConfiguration;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LoadConfiguration extends BaseLoadConfiguration
{

    /**
     * Get all of the configuration files for the application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return array
     */
    protected function getConfigurationFiles(Application $app)
    {
        $files = [ ];

        foreach ( Finder::create()->files()->name('*.php')->in($app->configPath()) as $file )
        {
            $nesting = $this->getConfigurationNesting($file);

            $files[ $nesting . basename($file->getRealPath(), '.php') ] = $file->getRealPath();
        }

        return $files;
    }
}
