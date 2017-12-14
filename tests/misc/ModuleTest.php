<?php
namespace KiwiSuiteMisc\Application;

use KiwiSuite\Application\Module\ModuleInterface;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

class ModuleTest implements ModuleInterface
{

    /**
     * @param ServiceManagerConfigurator $serviceManagerConfigurator
     */
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator): void
    {
        $serviceManagerConfigurator->addFactory(\DateTimeZone::class);
    }

    /**
     * @return string
     */
    public function getConfigDirectory(): string
    {
        return "";
    }

    /**
     * @return string
     */
    public function getBootstrapDirectory(): string
    {
        return "";
    }
}
