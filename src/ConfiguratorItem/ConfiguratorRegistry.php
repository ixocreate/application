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
     * @param string $configuratorInterface
     * @param $configurator
     */
    public function add(string $configuratorInterface, $configurator): void
    {
        $this->configurators[$configuratorInterface] = $configurator;
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
    public function get(string $name)
    {
        if ($this->has($name)) {
            return $this->configurators[$name];
        }

        throw new ArgumentNotFoundException(\sprintf("Configurator with name '%s' not found", $name));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->configurators);
    }
}
