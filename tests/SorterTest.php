<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Tests\Beverage;

use Caffeinated\Beverage\Contracts\Dependable;
use Caffeinated\Beverage\Sorter;

/**
 * This is the SorterTest.
 *
 * @package        Caffeinated\Tests
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class SorterTest extends TestCase
{

    /**
     * @var \Caffeinated\Beverage\Contracts\Sortable
     */
    protected $s;
    public function setUp()
    {
        parent::setUp();
        $this->s = new Sorter;
    }
    public function testStringDependencyList()
    {
        $this->s->add(array(
            'mother' => 'father',
            'father' => null,
            'couple' => 'father, mother',
        ));
        $this->expected = array(
            'father',
            'mother',
            'couple',
        );
        $this->result = $this->s->sort();

        $this->analyze();

        $this->assertSame($this->expected, array_values($this->result), $this->msg());
    }
    public function testDependableDependencyList()
    {
        $this->s->add(array(
            'father' => new SimpleDependable('father'),
            'mother' => new SimpleDependable('mother', 'father'),
            'couple' => new SimpleDependable('couple', 'mother,father'),
        ));
        $this->expected = array(
            'father',
            'mother',
            'couple',
        );
        $this->result = $this->s->sort();
        $this->analyze();

        $this->assertSame($this->expected, array_values($this->result), $this->msg());
    }
    public function testDependablesArrayDependencyList()
    {
        $this->s->add(array(
            new SimpleDependable('father'),
            new SimpleDependable('mother', 'father'),
            new SimpleDependable('couple', 'mother,father'),
        ));
        $this->expected = array(
            'father',
            'mother',
            'couple',
        );
        $this->result = $this->s->sort();
        $this->analyze();

        $this->assertSame($this->expected, array_values($this->result), $this->msg());
    }
    public function testSortGoodSet()
    {
        $this->s->add(array(
            'hello' => [],
            'helloGalaxy' => ['helloWorld'],
            'father' => [],
            'mother' => [],
            'child' => ['father', 'mother'],
            'helloWorld' => ['hello'],
            'family' => ['father', 'mother', 'child']
        ));
        $this->expected = array(
            'hello',
            'father',
            'mother',
            'child',
            'helloWorld',
            'family',
            'helloGalaxy',
        );
        $this->result = $this->s->sort();
        $this->analyze();

        $this->assertEmpty($this->s->getCircular());
        $this->assertEmpty($this->s->getMissing(), json_encode($this->s->getMissing(), JSON_PRETTY_PRINT));

        $this->assertSame($this->expected, array_values($this->result), $this->msg());
    }
    public function testSortMissingSet()
    {
        $this->s->add(array(
            'helloWorld' => [],
            'helloGalaxy' => ['helloWorld'],
            'father' => [],
            'child' => ['father', 'mother'],
            'family' => ['father', 'mother', 'child']
        ));
        $this->expected = array(
            'helloWorld',
            'helloGalaxy',
            'father'
        );
        $this->result = $this->s->sort();
        $this->analyze();
        $this->assertEmpty($this->s->getCircular());
        $this->assertTrue($this->s->isMissing('mother'));

        $this->assertTrue($this->s->hasMissing('child'));

        $this->assertTrue($this->s->hasMissing('family'));
        $this->assertSame($this->expected, array_values($this->result), $this->msg());
    }
    public function testSortCircularSet()
    {
        $this->s->add(array(
            'helloWorld' => ['hello'],
            'helloGalaxy' => ['helloWorld'],
            'mother' => ['father'],
            'child' => ['father', 'mother'],
            'father' => ['mother', 'baby'],
            'baby' => ['father']
        ));
        $this->expected = array( );
        $this->result = $this->s->sort();
        $this->analyze();
        $this->assertSame($this->expected, array_values($this->result), $this->msg());
        $this->assertTrue($this->s->isCircular('mother'));
        $this->assertTrue($this->s->isCircular('baby'));
        $this->assertTrue($this->s->isCircular('father'));

        $this->assertTrue($this->s->hasCircular('father'));

        $this->assertTrue($this->s->hasCircular('baby'));
        $this->assertTrue($this->s->hasCircular('mother'));
        $this->assertTrue($this->s->isMissing('hello'));
        $this->assertTrue($this->s->hasMissing('helloWorld'));
    }
    public function tearDown()
    {
        unset($this->expected);
        unset($this->result);
        unset($this->s);
    }
    protected function msg()
    {
        return sprintf(
            "Expected: %s\nResult: %s\n",
            join(", ", $this->expected),
            join(", ", $this->result)
        );
    }
    protected function analyze()
    {
        _d("\nLIST: [%s]", $this->result);
        {
            _d("\t [#] %12s %4s %4s %12s %4s %12s", 'item', 'dep\'es', 'missing', '', 'circular', '');
        foreach ($this->s->getHits() as $n => $c) {
            _d(
                "\t [%d] %12s %4s %4s %12s %4s %12s",
                $c,
                $n,
                $this->s->hasDependents($n) ? count($this->s->getDependents($n)) : 0,
                $this->s->hasMissing($n) ? count($this->s->getMissing($n)) : 0,
                $this->s->hasMissing($n) ? $this->s->getMissing($n) : '',
                $this->s->isCircular($n) ? count($this->s->getCircular($n)) : 0,
                $this->s->isCircular($n) ? $this->s->getCircular($n) : ''
            );
        }
        }
        _d("MISSING");
        foreach ($this->s->getMissing() as $key => $value) {
            _d("\t%s => %s", $key, $value);
        }
    }
}
function _d()
{
}


class SimpleDependable implements Dependable
{
    protected $handle;
    protected $deps;
    public function __construct($handle, $deps = [])
    {
        $this->handle = $handle;
        $this->deps = $deps;
    }
    public function getDependencies()
    {
        return $this->deps;
    }
    public function getHandle()
    {
        return $this->handle;
    }
}
