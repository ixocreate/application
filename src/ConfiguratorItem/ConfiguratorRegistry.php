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

use KiwiSuite\Application\Exception\ArgumentNotFoundException;

final class ConfiguratorRegistry
{

    /**
     * @var array
     */
    private $configurators = [];

    /**
     * @var array
     */
    private $configuratorInterfaces = [];

    /**
     * @param string $name
     * @param $configurator
     * @param string $configuratorInterface
     */
    public function addConfigurator(string $name, $configurator, string $configuratorInterface): void
    {
        $this->configurators[$name] = $configurator;
        $this->configuratorInterfaces[$configuratorInterface] = $name;
    }

    /**
     * @return array
     */
    public function getConfigurators(): array
    {
        return $this->configurators;
    }

    /**
     * @param string $name
     * @throws ArgumentNotFoundException
     * @return mixed
     */
    public function getConfigurator(string $name)
    {
        if ($this->hasConfigurator($name)) {
            return $this->configurators[$name];
        }

        throw new ArgumentNotFoundException(\sprintf("Configurator with name '%s' not found", $name));
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getConfiguratorByConfiguratorInterface(string $name)
    {
        if ($this->hasConfiguratorByConfiguratorInterface($name)) {
            return $this->getConfigurator($this->configuratorInterfaces[$name]);
        }

        throw new ArgumentNotFoundException(\sprintf("configurator from configurator interface with name '%s' not found", $name));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasConfigurator(string $name): bool
    {
        return \array_key_exists($name, $this->configurators);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasConfiguratorByConfiguratorInterface(string $name) : bool
    {
        if (!\array_key_exists($name, $this->configuratorInterfaces)) {
            return false;
        }

        return $this->hasConfigurator($this->configuratorInterfaces[$name]);
    }
}
