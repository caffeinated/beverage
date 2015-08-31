<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Caffeinated\Beverage;

use Caffeinated\Beverage\Vendor\Console\Dots;
use Caffeinated\Beverage\Vendor\Console\Spinner;
use Caffeinated\Beverage\Vendor\ConsoleColor;
use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * The abstract Command class. Other commands can extend this class to benefit from a larger toolset
 *
 * @package     Laradic\Console
 * @author      Robin Radic
 * @license     MIT
 * @copyright   2011-2015, Robin Radic
 * @link        http://radic.mit-license.org
 */
abstract class Command extends BaseCommand
{

    /**
     * @var bool
     */
    protected $allowSudo = false;

    /**
     * @var bool
     */
    protected $requireSudo = false;

    /**
     * @var \Caffeinated\Beverage\Vendor\ConsoleColor
     */
    protected $colors;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->colors = new ConsoleColor();
    }

    /**
     * @param $styles
     * @param $text
     * @return string
     * @throws \JakubOnderka\PhpConsoleColor\InvalidStyleException
     * @internal param array|string $style
     */
    public function colorize($styles, $text)
    {
        return $this->style($styles, $text);
    }

    /**
     * style
     *
     * @param $styles
     * @param $str
     * @return string
     * @throws \JakubOnderka\PhpConsoleColor\InvalidStyleException
     */
    protected function style($styles, $str)
    {
        return $this->colors->apply($styles, $str);
    }

    /**
     * Get the Laravel application instance.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function getLaravel()
    {
        return $this->laravel;
    }

    /**
     * hasRootAccess
     *
     * @return bool
     */
    public function hasRootAccess()
    {
        $path = '/root/.' . md5('_radic-cli-perm-test' . time());
        $root = (@file_put_contents($path, '1') === false ? false : true);
        if ($root !== false) {
            $this->getLaravel()->make('files')->delete($path);
        }

        return $root !== false;
    }

    /**
     * execute
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (! $this->allowSudo and ! $this->requireSudo and $this->hasRootAccess()) {
            $this->error('Cannot execute this command with root privileges');
            exit;
        }

        if ($this->requireSudo and ! $this->hasRootAccess()) {
            $this->error('This command requires root privileges');
            exit;
        }
        $this->getLaravel()->make('events')->fire('command.firing', $this->name);
        $return = null;
        if (method_exists($this, 'handle')) {
            $return = $this->handle();
        }
        if (method_exists($this, 'fire')) {
            $return = $this->fire();
        }
        $this->getLaravel()->make('events')->fire('command.fired', $this->name);

        return $return;
    }

    /**
     * @param mixed
     */
    public function dump($dump)
    {
        VarDumper::dump(func_get_args());
    }

    /**
     * arrayTable
     *
     * @param       $arr
     * @param array $header
     */
    protected function arrayTable($arr, array $header = [ 'Key', 'Value' ])
    {

        $rows = [ ];
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $val = print_r(array_slice($val, 0, 5), true);
            }
            $rows[] = [ (string)$key, (string)$val ];
        }
        $this->table($header, $rows);
    }

    /**
     * get colors value
     *
     * @return Vendor\ConsoleColor
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set the colors value
     *
     * @param Vendor\ConsoleColor $colors
     * @return Command
     */
    public function setColors($colors)
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * dots
     *
     * @param     $message
     * @param int $dots
     * @param int $interval
     * @return Dots
     */
    public function dots($message, $dots = 3, $interval = 100)
    {
        return new Dots($message, $dots, $interval);
    }

    /**
     * spinner
     *
     * @param     $message
     * @param int $interval
     * @return Spinner
     */
    public function spinner($message, $interval = 100)
    {
        return new Spinner($message, $interval);
    }

    /**
     * Write a string as error output.
     *
     * @param  string  $string
     * @return string
     */
    public function error($string)
    {
        $this->output->writeln("<error>$string</error>");
        return $string;
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function choice($question, array $choices, $default = null, $attempts = null, $multiple = null)
    {
        return parent::choice($question, $choices, $default, $attempts, $multiple);
    }
}
