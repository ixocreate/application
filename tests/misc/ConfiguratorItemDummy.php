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

use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;

class ConfiguratorItemDummy implements ConfiguratorItemInterface
{

    /**
     * @return mixed
     */
    public function getConfigurator()
    {
        return new class() {
            public $check = false;
        };
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'testObj';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'include_test.php';
    }

    /**
     * @param $configurator
     * @return \Serializable
     */
    public function getService($configurator): \Serializable
    {
    }
}
