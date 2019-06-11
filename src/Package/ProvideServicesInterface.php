<?php

declare(strict_types=1);

namespace Ixocreate\Application\Package;

use Ixocreate\Application\Service\ServiceRegistryInterface;

interface ProvideServicesInterface
{
    /**
     * @param ServiceRegistryInterface $serviceRegistry
     */
    public function provideServices(ServiceRegistryInterface $serviceRegistry): void;

}