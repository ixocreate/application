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
namespace KiwiSuite\Application;

use KiwiSuite\Application\Bootstrap\BootstrapItemResult;
use KiwiSuite\Application\Bootstrap\ConfigBootstrap;
use KiwiSuite\Application\Bootstrap\MiddlewareBootstrap;
use KiwiSuite\Application\Bootstrap\PipeBootstrap;
use KiwiSuite\Application\Bootstrap\RouteBootstrap;
use KiwiSuite\Application\Bootstrap\ServiceManagerBootstrap;
use KiwiSuite\Application\Http\Pipe\PipeConfig;
use KiwiSuite\Application\Http\Route\RouteConfig;
use KiwiSuite\ServiceManager\Resolver\CacheResolver;
use KiwiSuite\ServiceManager\Resolver\InMemoryResolver;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerSetup;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Expressive\Application;
use Zend\Expressive\Emitter\EmitterStack;
use Zend\Expressive\Router\FastRouteRouter;

final class HttpApplication extends AbstractApplication
{
    /**
     *
     */
    public function run(): void
    {
        $this->init();

        $emitter = new EmitterStack();
        $emitter->push(new SapiEmitter());

        $app = new Application(
            new FastRouteRouter(),
            $this->serviceManager->get('MiddlewareSubManager'),
            null,
            $emitter
        );

        $this->addPipes($app);
        $this->addRoutes($app);

        $app->run();
    }

    /**
     * @param ApplicationConfigurator $applicationConfigurator
     */
    protected function configureDefaultBootstrap(ApplicationConfigurator $applicationConfigurator): void
    {
        $applicationConfigurator->addBootstrapItem(ConfigBootstrap::class, 10);
        $applicationConfigurator->addBootstrapItem(ServiceManagerBootstrap::class, 20);
        $applicationConfigurator->addBootstrapItem(MiddlewareBootstrap::class, 100);
        $applicationConfigurator->addBootstrapItem(PipeBootstrap::class, 200);
        $applicationConfigurator->addBootstrapItem(RouteBootstrap::class, 300);
    }

    /**
     * @param Application $app
     */
    private function addPipes(Application $app): void
    {
        /** @var PipeConfig $pipeConfig */
        $pipeConfig = $this->bootstrapRegistry->get(PipeConfig::class);
        foreach ($pipeConfig->getGlobalPipe() as $globalPipe) {
            $app->pipe($globalPipe);
        }

        $app->pipeRoutingMiddleware();
        foreach ($pipeConfig->getRoutingPipe() as $routingPipe) {
            $app->pipe($routingPipe);
        }

        $app->pipeDispatchMiddleware();
        foreach ($pipeConfig->getDispatchPipe() as $dispatchPipe) {
            $app->pipe($dispatchPipe);
        }
    }

    /**
     * @param Application $app
     */
    private function addRoutes(Application $app): void
    {
        /** @var RouteConfig $routeConfig */
        $routeConfig = $this->bootstrapRegistry->get(RouteConfig::class);

        foreach ($routeConfig->getRoutes() as $route) {
            $app->route(
                $route['path'],
                $route['middleware'],
                $route['methods'],
                $route['name']
            );
        }
    }
}
