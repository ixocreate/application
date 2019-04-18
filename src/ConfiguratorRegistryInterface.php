<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

interface ConfiguratorRegistryInterface
{
    /**
     * @param string $name
     * @param ConfiguratorInterface $configurator
     */
    public function add(string $name, ConfiguratorInterface $configurator): void;

    /**
     * @return ConfiguratorInterface[]
     */
    public function all(): array;

    /**
     * @param string $name
     * @return ConfiguratorInterface
     */
    public function get(string $name);

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
}
