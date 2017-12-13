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

use KiwiSuite\Application\Bootstrap\ConfigBootstrap;
use KiwiSuite\Application\Bootstrap\ConsoleBootstrap;
use KiwiSuite\Application\Bootstrap\MiddlewareBootstrap;
use KiwiSuite\Application\Bootstrap\PipeBootstrap;
use KiwiSuite\Application\Bootstrap\RouteBootstrap;
use KiwiSuite\Application\Bootstrap\ServiceManagerBootstrap;
use KiwiSuite\Application\Http\Route\RouteConfig;
use KiwiSuite\ServiceManager\SubManager\SubManager;
use Symfony\Component\Console\Application;

final class ConsoleApplication extends AbstractApplication
{

    /**
     *
     */
    public function run(): void
    {
        $this->init();

        $application = new Application('kiwi', '0.0.1');

        /** @var SubManager $serverManager */
        $serverManager = $this->serviceManager->get('ConsoleCommandSubManager');

        foreach ($serverManager->getServiceManagerConfig()->getFactories() as $class => $factory) {
            $application->add($serverManager->get($class));
        }

        $application->run();
    }

    protected function configureDefaultBootstrap(ApplicationConfigurator $applicationConfigurator): void
    {
        $applicationConfigurator->addBootstrapItem(ConfigBootstrap::class, 10);
        $applicationConfigurator->addBootstrapItem(ServiceManagerBootstrap::class, 20);
        $applicationConfigurator->addBootstrapItem(MiddlewareBootstrap::class, 100);
        $applicationConfigurator->addBootstrapItem(PipeBootstrap::class, 200);
        $applicationConfigurator->addBootstrapItem(RouteBootstrap::class, 300);
        $applicationConfigurator->addBootstrapItem(ConsoleBootstrap::class, 400);
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
