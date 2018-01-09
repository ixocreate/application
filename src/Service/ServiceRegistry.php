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
namespace KiwiSuite\Application\Service;

use KiwiSuite\Application\Exception\ArgumentNotFoundException;

final class ServiceRegistry
{
    /**
     * @var array
     */
    private $services = [];

    /**
     * @param string $name
     * @param \Serializable $service
     */
    public function addService(string $name, \Serializable $service): void
    {
        $this->services[$name] = $service;
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * @param string $name
     * @throws ArgumentNotFoundException
     * @return mixed
     */
    public function getService(string $name): \Serializable
    {
        if ($this->hasService($name)) {
            return $this->services[$name];
        }

        throw new ArgumentNotFoundException(\sprintf("Service with name '%s' not found", $name));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasService(string $name): bool
    {
        return \array_key_exists($name, $this->services);
    }
}
