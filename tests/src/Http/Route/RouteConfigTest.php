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
namespace KiwiSuiteTest\Application\Http\Route;

use KiwiSuite\Application\Http\Route\RouteConfig;
use PHPUnit\Framework\TestCase;

class RouteConfigTest extends TestCase
{
    public function testGetRoutes()
    {
        $routing = [
            'path' => "/test",
            'middleware' => ['something'],
            'name' => 'test',
            'methods' => ['GET', 'POST'],
        ];

        $routeConfig = new RouteConfig([$routing]);

        $routes = $routeConfig->getRoutes();
        $this->assertEquals($routing['path'], $routes[0]['path']);
        $this->assertEquals($routing['middleware'], $routes[0]['middleware']);
        $this->assertEquals($routing['name'], $routes[0]['name']);
        $this->assertEquals($routing['methods'], $routes[0]['methods']);
    }
}
