<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage;

use Caffeinated\Beverage\Path;
use Caffeinated\Beverage\StubGenerator;
use Mockery as m;

/**
 * This is the StubGeneratorTest.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class StubGeneratorTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $fs;

    /**
     * @var \Mockery\MockInterface
     */
    protected $compiler;

    /**
     * @var \Caffeinated\Beverage\StubGenerator
     */
    protected $generator;

    public function setUp()
    {
        parent::setUp();
        $this->fs = m::mock('Caffeinated\\Beverage\\Filesystem');
        $this->compiler = m::mock('Illuminate\\View\\Compilers\\BladeCompiler');
        $this->generator = new StubGenerator($this->compiler, $this->fs);
    }

    public function testDirectoryGeneration()
    {
        $baseDir = base_path('test');
        foreach (['src', 'src2', 'src/make/this/happen'] as $dir) {
            $this->createDirGenTest($baseDir, $dir);
        }
    }

    protected function createDirGenTest($baseDir, $dir)
    {

        $this->fs->shouldReceive('exists')->once()->with(m::mustBe(Path::join($baseDir, $dir)))->andReturn(false);
        $this->fs->shouldReceive('makeDirectory')->once()->with(
            m::mustBe(Path::join($baseDir, $dir)),
            m::mustBe(0755),
            m::mustBe(true)
        )->andReturn(true);

        $generator = $this->generator->generateDirectoryStructure($baseDir, [$dir]);
        $this->assertInstanceOf(StubGenerator::class, $generator);
    }
}
