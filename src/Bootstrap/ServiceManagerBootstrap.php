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
     * @return BootstrapItemResult
     */
    public function bootstrap(ApplicationConfig $applicationConfig): BootstrapItemResult
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $this->addDefaults($serviceManagerConfigurator);

        if (\file_exists($applicationConfig->getBootstrapDirectory() . $this->bootstrapFilename)) {
            IncludeHelper::include(
                $applicationConfig->getBootstrapDirectory() . $this->bootstrapFilename,
                ['serviceManagerConfigurator' => $serviceManagerConfigurator]
            );
        }

        return new BootstrapItemResult([], [ServiceManagerConfig::class => $serviceManagerConfigurator->getServiceManagerConfig()]);
    }

    private function addDefaults(ServiceManagerConfigurator $serviceManagerConfigurator)
    {
        $serviceManagerConfigurator->addSubManager('MiddlewareSubManager', MiddlewareSubManagerFactory::class);
    }
}
