<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application;

use Ixocreate\Application\Package\PackageInterface;
use Ixocreate\Application\Package\ProvideServicesInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;

class PackageServiceDummy implements PackageInterface, ProvideServicesInterface
{
    public function provideServices(ServiceRegistryInterface $serviceRegistry): void
    {
    }

    public function getBootstrapItems(): array
    {
        return [];
    }

    public function getBootstrapDirectory(): ?string
    {
        return null;
    }

    public function getDependencies(): array
    {
        return [];
    }
}
