<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateMisc\Application;

use Ixocreate\Application\Bootstrap\BootstrapInterface;
use Ixocreate\Application\Service\ServiceRegistry;
use Ixocreate\ServiceManager\ServiceManager;

class BootstrapDummy implements BootstrapInterface
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
}
