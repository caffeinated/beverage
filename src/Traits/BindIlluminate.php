<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage\Traits;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\FileViewFinder;
use Symfony\Component\Finder\Finder;

/**
 * Bind Illuminate Trait
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
trait BindIlluminate
{
    /**
     * @var array
     */
    protected $illuminateClasses = [
        'files'  => 'Laradic\Support\Filesystem',
        'url'    => 'Illuminate\Routing\UrlGenerator',
        'events' => 'Illuminate\Events\Dispatcher'
    ];

    /**
     * @var string
     */
    protected $illuminateConfigPath;

    /**
     * @var string
     */
    protected $illuminateLangPath;

    /**
     * @var string
     */
    protected $illuminateViewPaths;

    /**
     * @var string
     */
    protected $illuminateCachePath;

    /**
     * Ensure the base event and file bindings are present. Required for binding anything else.
     *
     * @param  Container  $app
     * @return void
     */
    public function ensureIlluminateBase(Container $app = null)
    {
        if (! $app->bound('events')) {
            $app->singleton('events', $this->illuminateClasses['events']);
            $app['events']->fire('booting');
        }

        if (! $app->bound('files')) {
            $app->bindIf('files', $this->illuminateClasses['files']);
        }
    }

    /**
     * Bind the core classes to the Container
     *
     * @param  Container  $app
     * @return Container
     */
    public function bindIlluminateCore(Container $app = null)
    {
        if (! $app) {
            $app = new Container();
        }

        $this->ensureIlluminateBase($app);

        // Core classes
        $app->bindIf('url', $this->illuminateClasses[ 'url' ]);

        // Session and request
        $app->bindIf('session.manager', function ($app) {
            return new SessionManager($app);
        });

        $app->bindIf('session', function ($app) {
            return $app['session.manager']->driver('array');
        }, true);

        $app->bindIf('request', function ($app) {
            $request = Request::createFromGlobals();

            if (method_exists($request, 'setSessionStore')) {
                $request->setSessionStore($app['session']);
            } else {
                $request->setSession($app['session']);
            }

            return $request;
        }, true);

        // Config
        $app->bindIf('path.config', function ($app) {
            return $this->illuminateConfigPath;
        }, true);

        $app->bindIf('config', function ($app) {
            $config = new Repository;
            $this->loadIlluminateConfig($app, $config);

            return $config;
        }, true);

        // Localization
        $app->bindIf('translation.loader', function ($app) {
            return new FileLoader($app['files'], 'src/config');
        });

        $app->bindIf('translator', function ($app) {
            $loader = new FileLoader($app['files'], 'lang');

            return new Translator($loader, 'en');
        });

        return $app;
    }

    /**
     * bindIlluminateView
     *
     * @param  Container  $app
     * @param  array      $viewPaths
     * @param  string     $cachePath
     * @return Container
     */
    public function bindIlluminateView(Container $app = null, array $viewPaths = array(), $cachePath)
    {
        if (! $app) {
            $app = new Container();
        }

        $this->ensureIlluminateBase($app);

        $app->bindShared('view.engine.resolver', function (Container $app) {
            $resolver = new EngineResolver;

            $resolver->register('php', function () {
                return new PhpEngine;
            });

            $app->bindShared('blade.compiler', function (Container $app) {
                $cache = $this->illuminateCachePath;

                return new BladeCompiler($app['files'], $cache);
            });

            $resolver->register('blade', function () use ($app) {
                return new CompilerEngine($app['blade.compiler'], $app['files']);
            });

            return $resolver;
        });

        $app->bindShared('view.finder', function (Container $app) {
            $paths = $this->illuminateViewPaths;

            return new FileViewFinder($app['files'], $paths);
        });

        $app->bindShared('view', function (Container $app) {
            $env = new ViewFactory($app['view.engine.resolver'], $app['view.finder'], $app['events']);

            $env->setContainer($app);

            return $env;
        });

        return $app;
    }

    /**
     * Load the configuration items from all config files.
     *
     * @param  Container   $app
     * @param  Repository  $config
     * @return void
     */
    protected function loadIlluminateConfig(Container $app, Repository $config)
    {
        foreach ($this->getIlluminateConfig($app) as $key => $path) {
            $config->set($key, require $path);
        }
    }

    /**
     * Get all configuration files from the application.
     *
     * @param  Container  $app
     * @return array
     */
    protected function getIlluminateConfig(Container $app)
    {
        $files = array();

        foreach (Finder::create()->files()->name('*.php')->in($app['path.config']) as $file) {
            $files[basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        return $files;
    }
}
