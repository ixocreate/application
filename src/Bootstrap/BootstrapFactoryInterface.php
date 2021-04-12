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
use Ixocreate\Application\Service\ServiceHandlerInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\ServiceManager\ServiceManagerConfigInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

interface BootstrapFactoryInterface
{
    public function createApplicationConfigurator(string $bootstrapDirectory): ApplicationConfigurator;

    public function createServiceHandler(): ServiceHandlerInterface;

    public function createServiceManager(
        ServiceManagerConfigInterface $serviceManagerConfig,
        ApplicationConfig $applicationConfig,
        ServiceRegistryInterface $serviceRegistry
    ): ServiceManagerInterface;
}
