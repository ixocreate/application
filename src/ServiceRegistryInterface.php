<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

interface ServiceRegistryInterface
{
    /**
     * @param string $name
     * @param SerializableServiceInterface $service
     */
    public function add(string $name, SerializableServiceInterface $service): void;

    /**
     * @return SerializableServiceInterface[]
     */
    public function all(): array;

    /**
     * @param string $name
     * @return SerializableServiceInterface
     */
    public function get(string $name): SerializableServiceInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool;
}
