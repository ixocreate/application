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
namespace KiwiSuite\Application\Bootstrap;

use KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry;
use KiwiSuite\Application\Service\ServiceRegistry;
use KiwiSuite\ServiceManager\ServiceManager;

interface BootstrapInterface
{
    /**
     * @param ConfiguratorRegistry $configuratorRegistry
     */
    public function configure(ConfiguratorRegistry $configuratorRegistry) : void;

    /**
     * @param ServiceRegistry $serviceRegistry
     */
    public function addServices(ServiceRegistry $serviceRegistry) : void;

    /**
     * @return array|null
     */
    public function getConfiguratorItems() : ?array;

    /**
     * @return array|null
     */
    public function getDefaultConfig() : ?array;

    /**
     * @param ServiceManager $serviceManager
     */
    public function boot(ServiceManager $serviceManager) : void;
}
