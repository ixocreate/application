<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateMisc\Application;

use Ixocreate\Application\Module\ModuleInterface;
use Ixocreate\Application\Service\ServiceRegistry;
use Ixocreate\ServiceManager\ServiceManager;

class ModuleDummy implements ModuleInterface
{
    /**
     * @param \Ixocreate\Application\ConfiguratorItem\ConfiguratorRegistry $configuratorRegistry
     */
    public function configure(\Ixocreate\Application\ConfiguratorItem\ConfiguratorRegistry $configuratorRegistry): void
    {
    }

    /**
     * @return array|null
     */
    public function getDefaultConfig(): ?array
    {
        return null;
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function boot(ServiceManager $serviceManager): void
    {
    }

    /**
     * @return null|string
     */
    public function getBootstrapDirectory(): ?string
    {
        return null;
    }

    /**
     * @return null|string
     */
    public function getConfigDirectory(): ?string
    {
        return null;
    }

    /**
     * @param ServiceRegistry $serviceRegistry
     */
    public function addServices(ServiceRegistry $serviceRegistry): void
    {
    }

    /**
     * @return array|null
     */
    public function getConfiguratorItems(): ?array
    {
        return null;
    }

    /**
     * @return array|null
     */
    public function getBootstrapItems(): ?array
    {
        return null;
    }
}
