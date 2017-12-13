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
use KiwiSuite\Application\Http\Middleware\Factory\MiddlewareSubManagerFactory;
use KiwiSuite\Application\IncludeHelper;
use KiwiSuite\Application\Module\ModuleInterface;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

final class ServiceManagerBootstrap implements BootstrapInterface
{
    /**
     * @var string
     */
    private $bootstrapFilename = 'servicemanager.php';

    /**
     * @param ApplicationConfig $applicationConfig
     * @param BootstrapRegistry $bootstrapRegistry
     */
    public function bootstrap(ApplicationConfig $applicationConfig, BootstrapRegistry $bootstrapRegistry): void
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $this->addDefaults($serviceManagerConfigurator);

        $bootstrapDirectories = [
            $applicationConfig->getBootstrapDirectory(),
        ];

        foreach ($bootstrapRegistry->getModules() as $module) {
            $bootstrapDirectories[] = $module->getBootstrapDirectory();
        }

        foreach ($bootstrapDirectories as $directory) {
            if (\file_exists($directory . $this->bootstrapFilename)) {
                IncludeHelper::include(
                    $directory . $this->bootstrapFilename,
                    ['serviceManagerConfigurator' => $serviceManagerConfigurator]
                );
            }
        }

        $bootstrapRegistry->add(ServiceManagerConfig::class, $serviceManagerConfigurator->getServiceManagerConfig());
    }

    private function addDefaults(ServiceManagerConfigurator $serviceManagerConfigurator)
    {
        $serviceManagerConfigurator->addSubManager('MiddlewareSubManager', MiddlewareSubManagerFactory::class);
    }
}
