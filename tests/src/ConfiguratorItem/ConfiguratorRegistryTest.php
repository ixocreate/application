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
namespace IxocreateTest\Application\ConfiguratorItem;

use Ixocreate\Application\ConfiguratorItem\ConfiguratorRegistry;
use Ixocreate\Application\Exception\ArgumentNotFoundException;
use IxocreateMisc\Application\BootstrapDummy;
use IxocreateMisc\Application\ModuleDummy;
use PHPUnit\Framework\TestCase;

class ConfiguratorRegistryTest extends TestCase
{
    public function testConfigurators()
    {
        $configuratorRegistry = new ConfiguratorRegistry();
        $configuratorRegistry->add(ConfiguratorRegistryTest::class, new ModuleDummy());

        $this->assertArrayHasKey(ConfiguratorRegistryTest::class, $configuratorRegistry->getConfigurators());
        $this->assertTrue($configuratorRegistry->has(ConfiguratorRegistryTest::class));
        $this->assertFalse($configuratorRegistry->has(BootstrapDummy::class));
        $this->assertInstanceOf(ModuleDummy::class, $configuratorRegistry->get(ConfiguratorRegistryTest::class));
    }

    public function testGetConfiguratorException()
    {
        $configuratorRegistry = new ConfiguratorRegistry();

        $this->expectException(ArgumentNotFoundException::class);
        $configuratorRegistry->get(BootstrapDummy::class);
    }
}
