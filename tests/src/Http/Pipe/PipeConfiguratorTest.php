<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteTest\Application\Http\Pipe;

use KiwiSuite\Application\Http\Pipe\PipeConfigurator;
use PHPUnit\Framework\TestCase;

class PipeConfiguratorTest extends TestCase
{
    public function testAddGlobalPipe()
    {
        $configurator = new PipeConfigurator();
        $configurator->addGlobalPipe('middleware');

        $config = $configurator->getPipeConfig();

        $this->assertEquals($config->getGlobalPipe(), ['middleware']);
    }

    public function testGlobalPipePriority()
    {
        $configurator = new PipeConfigurator();
        $configurator->addGlobalPipe('middleware1');
        $configurator->addGlobalPipe('middleware2',100);
        $configurator->addGlobalPipe('middleware3',50);
        $configurator->addGlobalPipe('middleware4',150);

        $config = $configurator->getPipeConfig();

        $this->assertEquals($config->getGlobalPipe(), ['middleware4', 'middleware2', 'middleware1', 'middleware3']);
    }

    public function testAddRoutingPipe()
    {
        $configurator = new PipeConfigurator();
        $configurator->addRoutingPipe('middleware');

        $config = $configurator->getPipeConfig();

        $this->assertEquals($config->getRoutingPipe(), ['middleware']);
    }

    public function testRoutingPipePriority()
    {
        $configurator = new PipeConfigurator();
        $configurator->addRoutingPipe('middleware1');
        $configurator->addRoutingPipe('middleware2',100);
        $configurator->addRoutingPipe('middleware3',50);
        $configurator->addRoutingPipe('middleware4',150);

        $config = $configurator->getPipeConfig();

        $this->assertEquals($config->getRoutingPipe(), ['middleware4', 'middleware2', 'middleware1', 'middleware3']);
    }

    public function testAddDispatchPipe()
    {
        $configurator = new PipeConfigurator();
        $configurator->addDispatchPipe('middleware');

        $config = $configurator->getPipeConfig();

        $this->assertEquals($config->getDispatchPipe(), ['middleware']);
    }

    public function testDispatchPipePriority()
    {
        $configurator = new PipeConfigurator();
        $configurator->addDispatchPipe('middleware1');
        $configurator->addDispatchPipe('middleware2',100);
        $configurator->addDispatchPipe('middleware3',50);
        $configurator->addDispatchPipe('middleware4',150);

        $config = $configurator->getPipeConfig();

        $this->assertEquals($config->getDispatchPipe(), ['middleware4', 'middleware2', 'middleware1', 'middleware3']);
    }
}
