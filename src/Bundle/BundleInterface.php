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
namespace KiwiSuite\Application\Bundle;

use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

interface BundleInterface
{
    /**
     * @param ServiceManagerConfigurator $serviceManagerConfigurator
     */
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator) : void;

    /**
     * @return string
     */
    public function getConfigDirectory() : string;

    /**
     * @return string
     */
    public function getBootstrapDirectory() : string;
}
