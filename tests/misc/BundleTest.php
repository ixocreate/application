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
namespace KiwiSuiteMisc\Application;

use KiwiSuite\Application\Bundle\BundleInterface;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

class BundleTest implements BundleInterface
{

    /**
     * @param ServiceManagerConfigurator $serviceManagerConfigurator
     */
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator): void
    {
        $serviceManagerConfigurator->addFactory(\SplQueue::class);
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
