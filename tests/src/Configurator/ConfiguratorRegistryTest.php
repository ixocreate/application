<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\ConfiguratorItem;

use Ixocreate\Application\Configurator\ConfiguratorRegistry;
use Ixocreate\Application\Exception\ConfiguratorNotFoundException;
use Ixocreate\Misc\Application\BootstrapDummy;
use Ixocreate\Misc\Application\ConfiguratorDummy;
use PHPUnit\Framework\TestCase;

class ConfiguratorRegistryTest extends TestCase
{
    public function testConfigurators()
    {
        $configuratorRegistry = new ConfiguratorRegistry();
        $configuratorRegistry->add(ConfiguratorRegistryTest::class, new ConfiguratorDummy());

        $this->assertTrue($configuratorRegistry->has(ConfiguratorRegistryTest::class));
        $this->assertFalse($configuratorRegistry->has(BootstrapDummy::class));
        $this->assertInstanceOf(ConfiguratorDummy::class, $configuratorRegistry->get(ConfiguratorRegistryTest::class));
    }

    public function testGetConfiguratorException()
    {
        $configuratorRegistry = new ConfiguratorRegistry();

        $this->expectException(ConfiguratorNotFoundException::class);
        $configuratorRegistry->get(BootstrapDummy::class);
    }
}
