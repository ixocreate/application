<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Configurator;

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

        $configuratorList = [
            ConfiguratorDummy::class => new ConfiguratorDummy()
        ];

        foreach ($configuratorList as $name => $configurator) {
            $configuratorRegistry->add($name, $configurator);
        }


        $this->assertTrue($configuratorRegistry->has(ConfiguratorDummy::class));
        $this->assertFalse($configuratorRegistry->has(BootstrapDummy::class));
        $this->assertInstanceOf(ConfiguratorDummy::class, $configuratorRegistry->get(ConfiguratorDummy::class));
        $this->assertSame($configuratorList, $configuratorRegistry->all());
    }

    public function testGetConfiguratorException()
    {
        $configuratorRegistry = new ConfiguratorRegistry();

        $this->expectException(ConfiguratorNotFoundException::class);
        $configuratorRegistry->get(BootstrapDummy::class);
    }
}
