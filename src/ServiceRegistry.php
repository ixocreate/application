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
namespace KiwiSuite\Application;

use KiwiSuite\Application\Exception\ArgumentNotFoundException;
use KiwiSuite\Contract\Application\SerializableServiceInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;

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

        throw new ArgumentNotFoundException(\sprintf("Service with name '%s' not found", $name));
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