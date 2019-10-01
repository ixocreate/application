<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

use Ixocreate\Application\Bootstrap\BootstrapItemInclude;
use Ixocreate\Application\Service\ServiceHandler;
use Ixocreate\Application\Service\ServiceManagerConfig;
use Ixocreate\Application\Service\ServiceRegistry;
use Ixocreate\ServiceManager\ServiceManager;
use Ixocreate\ServiceManager\ServiceManagerSetup;

final class ApplicationBootstrap
{
    /**
     * @param string $bootstrapDirectory
     * @param ApplicationInterface $application
     * @return ServiceManager
     */
    public function bootstrap(string $bootstrapDirectory, ApplicationInterface $application): ServiceManager
    {
        $applicationConfig = $this->createApplicationConfig($bootstrapDirectory, $application);
        $serviceRegistry = (new ServiceHandler())->loadFromCache($applicationConfig);
        $serviceRegistry->add(ApplicationConfig::class, $applicationConfig);

        $serviceManager = $this->createServiceManager(
            $serviceRegistry->get(ServiceManagerConfig::class),
            $serviceRegistry
        );

        foreach ($applicationConfig->getPackages() as $package) {
            $package->boot($serviceManager);
        }

        return $serviceManager;
    }

    /**
     * @param string $bootstrapDirectory
     * @param ApplicationInterface $application
     * @return ApplicationConfig
     */
    private function createApplicationConfig(
        string $bootstrapDirectory,
        ApplicationInterface $application
    ): ApplicationConfig {
        $applicationConfigurator = new ApplicationConfigurator($bootstrapDirectory);

        $include = function($bootstrapFile) use ($applicationConfigurator) {
            if (\file_exists($bootstrapFile)) {
                BootstrapItemInclude::include(
                    $bootstrapFile,
                    ['application' => $applicationConfigurator]
                );
            }
        };

        $include($applicationConfigurator->getBootstrapDirectory() . 'application.php');
        $include($applicationConfigurator->getBootstrapDirectory() . $applicationConfigurator->getBootstrapEnvDirectory() . 'application.php');

        $application->configure($applicationConfigurator);

        return $applicationConfigurator->getApplicationConfig();
    }

    /**
     * @param ServiceManagerConfig $serviceManagerConfig
     * @param ServiceRegistry $serviceRegistry
     * @return ServiceManager
     */
    private function createServiceManager(
        ServiceManagerConfig $serviceManagerConfig,
        ServiceRegistry $serviceRegistry
    ): ServiceManager {
        return new ServiceManager(
            $serviceManagerConfig,
            new ServiceManagerSetup(
                $serviceRegistry->get(ApplicationConfig::class)->getPersistCacheDirectory() . 'servicemanager/',
                !$serviceRegistry->get(ApplicationConfig::class)->isDevelopment(),
                !$serviceRegistry->get(ApplicationConfig::class)->isDevelopment()
            ),
            $serviceRegistry->all()
        );
    }
}
