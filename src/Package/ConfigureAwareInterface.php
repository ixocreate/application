<?php

declare(strict_types=1);

namespace Ixocreate\Application\Package;

use Ixocreate\Application\Configurator\ConfiguratorRegistryInterface;

interface ConfigureAwareInterface
{
    /**
     * @param ConfiguratorRegistryInterface $configuratorRegistry
     */
    public function configure(ConfiguratorRegistryInterface $configuratorRegistry): void;
}