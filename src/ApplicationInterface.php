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

use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

interface ApplicationInterface
{
    /**
     *
     */
    public function run() : void;

    /**
     * @param ApplicationConfigurator $applicationConfigurator
     * @return mixed
     */
    public function configureApplicationConfig(ApplicationConfigurator $applicationConfigurator);

    /**
     * @param ServiceManagerConfigurator $serviceManagerConfigurator
     */
    public function configureServiceManager(ServiceManagerConfigurator $serviceManagerConfigurator) : void;
}
