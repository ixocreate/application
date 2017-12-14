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
/** @var \KiwiSuite\Application\ApplicationConfigurator $applicationConfigurator */
$applicationConfigurator->setConfigDirectory("something");
$applicationConfigurator->addModule(\KiwiSuiteMisc\Application\ModuleTest::class);
$applicationConfigurator->addBootstrapItem(\KiwiSuiteMisc\Application\BootstrapTest::class);
