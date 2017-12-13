<?php

declare(strict_types=1);
namespace KiwiSuite\Application\Bootstrap;

use KiwiSuite\Application\Module\ModuleInterface;

class BootstrapRegistry
{
    /**
     * @var array
     */
    private $modules;

    /**
     * @var array
     */
    private $services = [];

    /**
     * @var array
     */
    private $registry = [];

    /**
     * BootstrapCollection constructor.
     * @param array $modules
     */
    public function __construct(array $modules)
    {
        $this->modules = $modules;
    }

    /**
     * @return ModuleInterface[]
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @param string $name
     * @param $service
     */
    public function addService(string $name, $service): void
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
     * @return bool
     */
    public function hasService(string $name): bool
    {
        return \array_key_exists($name, $this->services);
    }

    /**
     * @param string $name
     * @param $item
     */
    public function add(string $name, $item): void
    {
        $this->registry[$name] = $item;
    }

    /**
     * @return array
     */
    public function getRegistry(): array
    {
        return $this->registry;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->registry);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function get(string $name)
    {
        if ($this->has($name)) {
            return $this->registry[$name];
        }
        //TODO: create application exception and improve text
        throw new \Exception('Registry Item not found: ' . $name);
    }
}
