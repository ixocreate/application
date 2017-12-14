<?php
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
