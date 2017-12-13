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
use KiwiSuite\Application\Http\Pipe\PipeConfig;
use KiwiSuite\Application\Http\Pipe\PipeConfigurator;
use KiwiSuite\Application\IncludeHelper;
use KiwiSuite\Application\Module\ModuleInterface;

final class PipeBootstrap implements BootstrapInterface
{
    /**
     * @var string
     */
    private $bootstrapFilename = 'pipe.php';

    /**
     * @param ApplicationConfig $applicationConfig
     * @param BootstrapRegistry $bootstrapRegistry
     */
    public function bootstrap(ApplicationConfig $applicationConfig, BootstrapRegistry $bootstrapRegistry): void
    {
        $pipeConfigurator = new PipeConfigurator();
        $this->addDefaults($pipeConfigurator);

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
                    ['pipeConfigurator' => $pipeConfigurator]
                );
            }
        }

        $bootstrapRegistry->add(PipeConfig::class, $pipeConfigurator->getPipeConfig());
    }

    private function addDefaults(PipeConfigurator $pipeConfigurator)
    {
        //TODO add Default Pipes
    }
}
