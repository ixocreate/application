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
namespace KiwiSuite\Application\ConfiguratorItem;

interface ConfiguratorItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator();

    /**
     * @return string
     */
    public function getVariableName() : string;

    /**
     * @return string
     */
    public function getFileName() : string;

    /**
     * @param $configurator
     * @return \Serializable
     */
    public function getService($configurator) : \Serializable;
}
