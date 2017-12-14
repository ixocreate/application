<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Application\Bootstrap;

use KiwiSuite\Application\ApplicationConfig;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

interface BootstrapInterface
{
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator) : void;

    /**
     * @param ApplicationConfig $applicationConfig
     * @param BootstrapRegistry $bootstrapCollection
     */
    public function bootstrap(ApplicationConfig $applicationConfig, BootstrapRegistry $bootstrapCollection) : void;
}
