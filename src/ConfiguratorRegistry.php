<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

use Ixocreate\Application\Exception\ArgumentNotFoundException;
use Ixocreate\Application\ConfiguratorInterface;
use Ixocreate\Application\ConfiguratorRegistryInterface;

final class ConfiguratorRegistry implements ConfiguratorRegistryInterface
{
    /**
     * @var array
     */
    private $configurators = [];

    /**
     * @param string $configuratorInterface
     * @param ConfiguratorInterface $configurator
     */
    public function add(string $configuratorInterface, ConfiguratorInterface $configurator): void
    {
        $this->configurators[$configuratorInterface] = $configurator;
    }

    /**
     * @return ConfiguratorInterface[]
     */
    public function all(): array
    {
        return $this->configurators;
    }

    /**
     * @param string $name
     * @throws ArgumentNotFoundException
     * @return ConfiguratorInterface
     */
    public function get(string $name): ConfiguratorInterface
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
