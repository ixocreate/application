<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Service\Registry;

use Ixocreate\Application\Exception\ServiceNotFoundException;
use Ixocreate\Application\Service\SerializableServiceInterface;

final class ServiceRegistry implements ServiceRegistryInterface
{
    /**
     * @var array
     */
    private $services = [];

    /**
     * @param string $name
     * @param SerializableServiceInterface $service
     */
    public function add(string $name, SerializableServiceInterface $service): void
    {
        $this->services[$name] = $service;
    }

    /**
     * @return SerializableServiceInterface[]
     */
    public function all(): array
    {
        return $this->services;
    }

    /**
     * @param string $name
     * @throws ArgumentNotFoundException
     * @return mixed
     */
    public function get(string $name): SerializableServiceInterface
    {
        if ($this->has($name)) {
            return $this->services[$name];
        }

        throw new ServiceNotFoundException(\sprintf("Service with name '%s' not found", $name));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->services);
    }
}
