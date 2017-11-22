<?php
namespace KiwiSuiteTest\Application\Http\Route;

use KiwiSuite\Application\Exception\InvalidArgumentException;
use KiwiSuite\Application\Http\Route\RouteConfigurator;
use PHPUnit\Framework\TestCase;

class RouteConfiguratorTest extends TestCase
{
    public function testAddRoute()
    {
        $routeConfig = [
            'path' => "/test",
            'middleware' => ['something'],
            'name' => 'test',
            'methods' => ['GET', 'POST']
        ];

        $routeConfigurator = new RouteConfigurator();
        $routeConfigurator->addRoute($routeConfig['path'], $routeConfig['middleware'], $routeConfig['name'], $routeConfig['methods']);

        $routes = $routeConfigurator->getRouteConfig()->getRoutes();
        $this->assertEquals($routeConfig['path'], $routes[0]['path']);
        $this->assertEquals($routeConfig['middleware'], $routes[0]['middleware']);
        $this->assertEquals($routeConfig['name'], $routes[0]['name']);
        $this->assertEquals($routeConfig['methods'], $routes[0]['methods']);
    }

    public function testAddGet()
    {
        $routeConfig = [
            'path' => "/test",
            'middleware' => ['something'],
            'name' => 'test',
        ];

        $routeConfigurator = new RouteConfigurator();
        $routeConfigurator->addGet($routeConfig['path'], $routeConfig['middleware'], $routeConfig['name']);

        $routes = $routeConfigurator->getRouteConfig()->getRoutes();
        $this->assertEquals($routeConfig['path'], $routes[0]['path']);
        $this->assertEquals($routeConfig['middleware'], $routes[0]['middleware']);
        $this->assertEquals($routeConfig['name'], $routes[0]['name']);
        $this->assertEquals(['GET'], $routes[0]['methods']);
    }

    public function testAddPost()
    {
        $routeConfig = [
            'path' => "/test",
            'middleware' => ['something'],
            'name' => 'test',
        ];

        $routeConfigurator = new RouteConfigurator();
        $routeConfigurator->addPost($routeConfig['path'], $routeConfig['middleware'], $routeConfig['name']);

        $routes = $routeConfigurator->getRouteConfig()->getRoutes();
        $this->assertEquals($routeConfig['path'], $routes[0]['path']);
        $this->assertEquals($routeConfig['middleware'], $routes[0]['middleware']);
        $this->assertEquals($routeConfig['name'], $routes[0]['name']);
        $this->assertEquals(['POST'], $routes[0]['methods']);
    }

    public function testAddDelete()
    {
        $routeConfig = [
            'path' => "/test",
            'middleware' => ['something'],
            'name' => 'test',
        ];

        $routeConfigurator = new RouteConfigurator();
        $routeConfigurator->addDelete($routeConfig['path'], $routeConfig['middleware'], $routeConfig['name']);

        $routes = $routeConfigurator->getRouteConfig()->getRoutes();
        $this->assertEquals($routeConfig['path'], $routes[0]['path']);
        $this->assertEquals($routeConfig['middleware'], $routes[0]['middleware']);
        $this->assertEquals($routeConfig['name'], $routes[0]['name']);
        $this->assertEquals(['DELETE'], $routes[0]['methods']);
    }

    public function testAddPut()
    {
        $routeConfig = [
            'path' => "/test",
            'middleware' => ['something'],
            'name' => 'test',
        ];

        $routeConfigurator = new RouteConfigurator();
        $routeConfigurator->addPut($routeConfig['path'], $routeConfig['middleware'], $routeConfig['name']);

        $routes = $routeConfigurator->getRouteConfig()->getRoutes();
        $this->assertEquals($routeConfig['path'], $routes[0]['path']);
        $this->assertEquals($routeConfig['middleware'], $routes[0]['middleware']);
        $this->assertEquals($routeConfig['name'], $routes[0]['name']);
        $this->assertEquals(['PUT'], $routes[0]['methods']);
    }

    public function testAddPatch()
    {
        $routeConfig = [
            'path' => "/test",
            'middleware' => ['something'],
            'name' => 'test',
        ];

        $routeConfigurator = new RouteConfigurator();
        $routeConfigurator->addPatch($routeConfig['path'], $routeConfig['middleware'], $routeConfig['name']);

        $routes = $routeConfigurator->getRouteConfig()->getRoutes();
        $this->assertEquals($routeConfig['path'], $routes[0]['path']);
        $this->assertEquals($routeConfig['middleware'], $routes[0]['middleware']);
        $this->assertEquals($routeConfig['name'], $routes[0]['name']);
        $this->assertEquals(['PATCH'], $routes[0]['methods']);
    }

    public function testInvalidMethod()
    {
        $this->expectException(InvalidArgumentException::class);

        $routeConfig = [
            'path' => "/test",
            'middleware' => ['something'],
            'name' => 'test',
            'methods' => ['SOMETHING']
        ];

        $routeConfigurator = new RouteConfigurator();
        $routeConfigurator->addRoute($routeConfig['path'], $routeConfig['middleware'], $routeConfig['name'], $routeConfig['methods']);

    }
}
