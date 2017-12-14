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
namespace KiwiSuite\Application;

use KiwiSuite\Application\Bootstrap\BootstrapRegistry;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;
use KiwiSuite\ServiceManager\ServiceManagerSetup;

final class Bootstrap
{
    /**
     * @param string $bootstrapDirectory
     * @return ServiceManager
     */
    public function bootstrap(string $bootstrapDirectory): ServiceManager
    {
        $applicationConfig = $this->createApplicationConfig($bootstrapDirectory);
        $bootstrapRegistry = new BootstrapRegistry($applicationConfig->getModules());
        $bootstrapRegistry->addService(ApplicationConfig::class, $applicationConfig);

        foreach ($applicationConfig->getBootstrapQueue() as $bootstrapItem) {
            (new $bootstrapItem())->bootstrap($applicationConfig, $bootstrapRegistry);
        }


        return $this->createServiceManager(
            $this->createServiceManagerConfig($applicationConfig),
            $bootstrapRegistry
        );
    }

    /**
     * @param string $bootstrapDirectory
     * @return ApplicationConfig
     */
    private function createApplicationConfig(string $bootstrapDirectory) : ApplicationConfig
    {
        $bootstrapDirectory = IncludeHelper::normalizePath($bootstrapDirectory);
        $applicationConfigurator = new ApplicationConfigurator($bootstrapDirectory);

        if (\file_exists($bootstrapDirectory . 'application.php')) {
            IncludeHelper::include(
                $bootstrapDirectory . 'application.php',
                ['applicationConfigurator' => $applicationConfigurator]
            );
        }

        return $applicationConfigurator->getApplicationConfig();
    }

    /**
     * @param ApplicationConfig $applicationConfig
     * @return ServiceManagerConfig
     */
    private function createServiceManagerConfig(ApplicationConfig $applicationConfig) : ServiceManagerConfig
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        foreach ($applicationConfig->getBootstrapQueue() as $bootstrapItem) {
            $bootstrapItem->configureServiceManager($serviceManagerConfigurator);
        }

        foreach ($applicationConfig->getModules() as $module) {
            $module->configureServiceManager($serviceManagerConfigurator);
        }

        $includeFile = IncludeHelper::normalizePath($applicationConfig->getBootstrapDirectory()) . 'servicemanager.php';
        if (\file_exists($includeFile)) {
            IncludeHelper::include(
                $includeFile,
                ['serviceManagerConfigurator' => $serviceManagerConfigurator]
            );
        }

        return $serviceManagerConfigurator->getServiceManagerConfig();
    }

    /**
     * @param ServiceManagerConfig $serviceManagerConfig
     * @param BootstrapRegistry $bootstrapRegistry
     * @return ServiceManager
     */
    private function createServiceManager(ServiceManagerConfig $serviceManagerConfig, BootstrapRegistry $bootstrapRegistry): ServiceManager
    {
        return new ServiceManager(
            $serviceManagerConfig,
            new ServiceManagerSetup(
                $bootstrapRegistry->getService(ApplicationConfig::class)->getPersistCacheDirectory() . 'servicemanager/',
                !$bootstrapRegistry->getService(ApplicationConfig::class)->isDevelopment()
            ),
            $bootstrapRegistry->getServices()
        );
    }
}
