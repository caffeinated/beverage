<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

use Illuminate\View\Compilers\BladeCompiler;

/**
 * This is the StubGenerator.
 *
 * @package        Caffeinated\Beverage
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class StubGenerator
{
    /**
     * @var \Illuminate\View\Compilers\BladeCompiler
     */
    protected $compiler;

    /**
     * @var \Caffeinated\Beverage\Filesystem
     */
    protected $files;

    /** Instantiates the class
     *
     * @param \Illuminate\View\Compilers\BladeCompiler $compiler
     * @param \Caffeinated\Beverage\Filesystem         $files
     */
    public function __construct(BladeCompiler $compiler, Filesystem $files)
    {
        $this->compiler = $compiler;
        $this->files    = $files;
    }

    /**
     * render
     *
     * @param       $string
     * @param array $vars
     * @return string
     */
    public function render($string, array $vars = array())
    {
        $fileName = uniqid(time(), false);
        $this->generateDirectoryStructure(storage_path(), [ 'caffeinated/stubs' ]);
        $path = storage_path("caffeinated/stubs/{$fileName}");
        $this->files->put($path, $this->compiler->compileString($string));

        if (is_array($vars) && ! empty($vars)) {
            extract($vars);
        }

        ob_start();
        include($path);
        $var = ob_get_contents();
        ob_end_clean();

        $this->files->delete($path);

        return $var;
    }

    /**
     * generate
     *
     * @param string $stubDir
     * @param string $destDir
     * @param array  $files
     * @param array  $vars
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function generate($stubDir, $destDir, array $files = [ ], array $vars = [ ])
    {
        foreach ($files as $stubFile => $destFile) {
            foreach (array_dot($vars) as $key => $val) {
                $destFile = Str::replace($destFile, '{{' . $key . '}}', $val);
            }

            $stubPath    = Path::join($stubDir, $stubFile);
            $destPath    = Path::join($destDir, $destFile);
            $destDirPath = Path::getDirectory($destPath);

            if (! $this->files->exists($destDirPath)) {
                $this->files->makeDirectory($destDirPath, 0755, true);
            }

            $rendered = $this->render($this->files->get($stubPath), $vars);
            $this->files->put($destPath, $rendered);
        }
        return $this;
    }

    /**
     * generateDirectoryStructure
     *
     * @param       $destDir
     * @param array $dirs
     * @return $this
     */
    public function generateDirectoryStructure($destDir, array $dirs = [ ])
    {
        foreach ($dirs as $dirPath) {
            $dirPath = Path::join($destDir, $dirPath);
            if (! $this->files->exists($dirPath)) {
                $this->files->makeDirectory($dirPath, 0755, true);
            }
        }
        return $this;
    }
}
