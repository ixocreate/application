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

final class PipeBootstrap implements BootstrapInterface
{
    /**
     * @var string
     */
    private $bootstrapFilename = 'pipe.php';

    /**
     * @param ApplicationConfig $applicationConfig
     * @return BootstrapItemResult
     */
    public function bootstrap(ApplicationConfig $applicationConfig): BootstrapItemResult
    {
        $pipeConfigurator = new PipeConfigurator();
        $this->addDefaults($pipeConfigurator);

        if (\file_exists($applicationConfig->getBootstrapDirectory() . $this->bootstrapFilename)) {
            IncludeHelper::include(
                $applicationConfig->getBootstrapDirectory() . $this->bootstrapFilename,
                ['pipeConfigurator' => $pipeConfigurator]
            );
        }

        return new BootstrapItemResult([], [PipeConfig::class => $pipeConfigurator->getPipeConfig()]);
    }

    private function addDefaults(PipeConfigurator $pipeConfigurator)
    {
        //TODO add Default Pipes
    }
}
