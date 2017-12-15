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
     * @param ApplicationInterface $application
     * @return ServiceManager
     */
    public function bootstrap(string $bootstrapDirectory, ApplicationInterface $application): ServiceManager
    {
        $applicationConfig = $this->createApplicationConfig($bootstrapDirectory, $application);
        $bootstrapRegistry = new BootstrapRegistry($applicationConfig->getModules());
        $bootstrapRegistry->addService(ApplicationConfig::class, $applicationConfig);

        foreach ($applicationConfig->getBootstrapQueue() as $bootstrapItem) {
            (new $bootstrapItem())->bootstrap($applicationConfig, $bootstrapRegistry);
        }


        return $this->createServiceManager(
            $this->createServiceManagerConfig($applicationConfig, $application),
            $bootstrapRegistry
        );
    }

    /**
     * @param string $bootstrapDirectory
     * @param ApplicationInterface $application
     * @return ApplicationConfig
     */
    private function createApplicationConfig(string $bootstrapDirectory, ApplicationInterface $application) : ApplicationConfig
    {
        $bootstrapDirectory = IncludeHelper::normalizePath($bootstrapDirectory);
        $applicationConfigurator = new ApplicationConfigurator($bootstrapDirectory);

        $application->configureApplicationConfig($applicationConfigurator);

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
     * @param ApplicationInterface $application
     * @return ServiceManagerConfig
     */
    private function createServiceManagerConfig(ApplicationConfig $applicationConfig, ApplicationInterface $application) : ServiceManagerConfig
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $application->configureServiceManager($serviceManagerConfigurator);

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
