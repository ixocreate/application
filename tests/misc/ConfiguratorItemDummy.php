<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateMisc\Application;

use Ixocreate\Application\ConfiguratorItem\ConfiguratorItemInterface;

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
