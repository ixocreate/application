<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Bootstrap;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Application\Console\ConsoleBootstrapItem;
use Ixocreate\Application\Http\Middleware\MiddlewareBootstrapItem;
use Ixocreate\Application\Http\Pipe\PipeBootstrapItem;
use Ixocreate\Application\Publish\PublishBootstrapItem;
use Ixocreate\Application\Service\ServiceHandler;
use Ixocreate\Application\Service\ServiceHandlerInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Application\Uri\ApplicationUriBootstrapItem;
use Ixocreate\ServiceManager\ServiceManager;
use Ixocreate\ServiceManager\ServiceManagerConfigInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\ServiceManager\ServiceManagerSetup;

final class BootstrapFactory implements BootstrapFactoryInterface
{
    public function createApplicationConfigurator(string $bootstrapDirectory): ApplicationConfigurator
    {
        $configurator = new ApplicationConfigurator($bootstrapDirectory);

        $configurator->addBootstrapItem(ApplicationUriBootstrapItem::class);
        $configurator->addBootstrapItem(ConsoleBootstrapItem::class);
        $configurator->addBootstrapItem(MiddlewareBootstrapItem::class);
        $configurator->addBootstrapItem(PipeBootstrapItem::class);
        $configurator->addBootstrapItem(PublishBootstrapItem::class);

        return $configurator;
    }

    public function createServiceHandler(): ServiceHandlerInterface
    {
        return new ServiceHandler();
    }

    public function createServiceManager(
        ServiceManagerConfigInterface $serviceManagerConfig,
        ApplicationConfig $applicationConfig,
        ServiceRegistryInterface $serviceRegistry
    ): ServiceManagerInterface {
        return new ServiceManager(
            $serviceManagerConfig,
            new ServiceManagerSetup(
                $applicationConfig->getPersistCacheDirectory() . 'servicemanager/',
                !$applicationConfig->isDevelopment(),
                !$applicationConfig->isDevelopment()
            ),
            $serviceRegistry->all()
        );
    }
}
