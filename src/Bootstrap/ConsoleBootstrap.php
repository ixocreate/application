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
use KiwiSuite\Application\Console\ConsoleConfigurator;
use KiwiSuite\Application\Console\ConsoleServiceManagerConfig;
use KiwiSuite\Application\Http\Route\RouteConfig;
use KiwiSuite\Application\IncludeHelper;
use KiwiSuite\Application\Module\ModuleInterface;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

final class ConsoleBootstrap implements BootstrapInterface
{
    /**
     * @var string
     */
    private $bootstrapFilename = 'console.php';

    /**
     * @param ApplicationConfig $applicationConfig
     * @param BootstrapRegistry $bootstrapRegistry
     */
    public function bootstrap(ApplicationConfig $applicationConfig, BootstrapRegistry $bootstrapRegistry): void
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator(ConsoleServiceManagerConfig::class);

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

        $bootstrapRegistry->add(ConsoleServiceManagerConfig::class, $serviceManagerConfigurator->getServiceManagerConfig());
    }
}
