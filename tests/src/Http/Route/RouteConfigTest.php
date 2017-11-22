<?php
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
            'methods' => ['GET', 'POST']
        ];

        $routeConfig = new RouteConfig([$routing]);

        $routes = $routeConfig->getRoutes();
        $this->assertEquals($routing['path'], $routes[0]['path']);
        $this->assertEquals($routing['middleware'], $routes[0]['middleware']);
        $this->assertEquals($routing['name'], $routes[0]['name']);
        $this->assertEquals($routing['methods'], $routes[0]['methods']);
    }
}
