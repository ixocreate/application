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

use KiwiSuite\Application\ApplicationConfig;
use KiwiSuite\Application\Bootstrap\BootstrapInterface;
use KiwiSuite\Application\Bootstrap\BootstrapRegistry;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

class BootstrapTest implements BootstrapInterface
{
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator): void
    {
        $serviceManagerConfigurator->addFactory(\DateInterval::class);
    }

    /**
     * @param ApplicationConfig $applicationConfig
     * @param BootstrapRegistry $bootstrapCollection
     */
    public function bootstrap(ApplicationConfig $applicationConfig, BootstrapRegistry $bootstrapCollection): void
    {
    }
}
