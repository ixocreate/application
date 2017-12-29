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
namespace KiwiSuiteMisc\Application;

use KiwiSuite\Application\ApplicationConfigurator;
use KiwiSuite\Application\ApplicationInterface;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

class ApplicationTest implements ApplicationInterface
{
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator): void
    {
        $serviceManagerConfigurator->addFactory(\DatePeriod::class);
    }

    /**
     *
     */
    public function run(): void
    {
    }

    /**
     * @param ApplicationConfigurator $applicationConfigurator
     * @return mixed
     */
    public function configureApplicationConfig(ApplicationConfigurator $applicationConfigurator)
    {
        $applicationConfigurator->addModule(ModuleTest::class);
        $applicationConfigurator->addBundle(BundleTest::class);
    }
}
