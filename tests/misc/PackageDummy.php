<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application;

use Ixocreate\Application\Configurator\ConfiguratorRegistryInterface;
use Ixocreate\Application\Package\PackageInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

class PackageDummy implements PackageInterface
{
    public function configure(ConfiguratorRegistryInterface $configuratorRegistry): void
    {
    }

    public function addServices(ServiceRegistryInterface $serviceRegistry): void
    {
    }

    public function getBootstrapItems(): ?array
    {
        return null;
    }

    public function getConfigProvider(): ?array
    {
        return null;
    }

    public function boot(ServiceManagerInterface $serviceManager): void
    {
    }

    public function getBootstrapDirectory(): ?string
    {
        return null;
    }

    public function getDependencies(): ?array
    {
        return null;
    }
}
