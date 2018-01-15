<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteMisc\Application;

use KiwiSuite\Application\Bootstrap\BootstrapInterface;
use KiwiSuite\Application\Service\ServiceRegistry;
use KiwiSuite\ServiceManager\ServiceManager;

class BootstrapDummy implements BootstrapInterface
{

    /**
     * @param \KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry $configuratorRegistry
     */
    public function configure(\KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry $configuratorRegistry): void
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
}
