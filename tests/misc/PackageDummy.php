<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateMisc\Application;

use Ixocreate\Contract\Application\ConfiguratorRegistryInterface;
use Ixocreate\Contract\Application\PackageInterface;
use Ixocreate\Contract\Application\ServiceRegistryInterface;
use Ixocreate\Contract\ServiceManager\ServiceManagerInterface;

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

    public function getConfigDirectory(): ?string
    {
        return null;
    }

    public function getDependencies(): ?array
    {
        return null;
    }
}
