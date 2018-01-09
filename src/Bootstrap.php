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
namespace KiwiSuite\Application;

use KiwiSuite\Application\Service\ServiceHandler;
use KiwiSuite\Application\Service\ServiceRegistry;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
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
        $serviceRegistry = (new ServiceHandler())->loadFromCache($application, $applicationConfig);
        $serviceRegistry->addService(ApplicationConfig::class, $applicationConfig);

        $serviceManager = $this->createServiceManager(
            $serviceRegistry->getService(ServiceManagerConfig::class),
            $serviceRegistry
        );

        foreach ($applicationConfig->getBootstrapQueue() as $bootstrapItem) {
            $bootstrapItem->boot($serviceManager);
        }

        foreach ($applicationConfig->getModules() as $module) {
            $module->boot($serviceManager);
        }

        return $serviceManager;
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
     * @param ServiceManagerConfig $serviceManagerConfig
     * @param ServiceRegistry $serviceRegistry
     * @return ServiceManager
     */
    private function createServiceManager(ServiceManagerConfig $serviceManagerConfig, ServiceRegistry $serviceRegistry): ServiceManager
    {
        return new ServiceManager(
            $serviceManagerConfig,
            new ServiceManagerSetup(
                $serviceRegistry->getService(ApplicationConfig::class)->getPersistCacheDirectory() . 'servicemanager/',
                !$serviceRegistry->getService(ApplicationConfig::class)->isDevelopment()
            ),
            $serviceRegistry->getServices()
        );
    }
}
