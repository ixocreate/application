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
namespace KiwiSuiteTest\Application\ConfiguratorItem;

use KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry;
use KiwiSuite\Application\Exception\ArgumentNotFoundException;
use KiwiSuiteMisc\Application\BootstrapDummy;
use KiwiSuiteMisc\Application\ModuleDummy;
use PHPUnit\Framework\TestCase;

class ConfiguratorRegistryTest extends TestCase
{
    public function testConfigurators()
    {
        $configuratorRegistry = new ConfiguratorRegistry();
        $configuratorRegistry->addConfigurator(ModuleDummy::class, new ModuleDummy(), ConfiguratorRegistryTest::class);
        $this->assertArrayHasKey(ModuleDummy::class, $configuratorRegistry->getConfigurators());
        $this->assertTrue($configuratorRegistry->hasConfigurator(ModuleDummy::class));
        $this->assertFalse($configuratorRegistry->hasConfigurator(BootstrapDummy::class));
        $this->assertTrue($configuratorRegistry->hasConfiguratorByConfiguratorInterface(ConfiguratorRegistryTest::class));
        $this->assertFalse($configuratorRegistry->hasConfiguratorByConfiguratorInterface(BootstrapDummy::class));
        $this->assertInstanceOf(ModuleDummy::class, $configuratorRegistry->getConfigurator(ModuleDummy::class));
        $this->assertInstanceOf(ModuleDummy::class, $configuratorRegistry->getConfiguratorByConfiguratorInterface(ConfiguratorRegistryTest::class));
    }

    public function testGetConfiguratorException()
    {
        $configuratorRegistry = new ConfiguratorRegistry();

        $this->expectException(ArgumentNotFoundException::class);
        $configuratorRegistry->getConfigurator(BootstrapDummy::class);
    }

    public function testGetConfiguratorByConfiguratorInterface()
    {
        $configuratorRegistry = new ConfiguratorRegistry();

        $this->expectException(ArgumentNotFoundException::class);
        $configuratorRegistry->getConfiguratorByConfiguratorInterface(BootstrapDummy::class);
    }
}
