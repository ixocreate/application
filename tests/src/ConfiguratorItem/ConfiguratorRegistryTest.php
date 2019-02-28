<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateTest\Application\ConfiguratorItem;

use Ixocreate\Application\ConfiguratorItem\ConfiguratorRegistry;
use Ixocreate\Application\Exception\ArgumentNotFoundException;
use IxocreateMisc\Application\BootstrapDummy;
use IxocreateMisc\Application\PackageDummy;
use PHPUnit\Framework\TestCase;

class ConfiguratorRegistryTest extends TestCase
{
    public function testConfigurators()
    {
        $configuratorRegistry = new ConfiguratorRegistry();
        $configuratorRegistry->add(ConfiguratorRegistryTest::class, new PackageDummy());

        $this->assertArrayHasKey(ConfiguratorRegistryTest::class, $configuratorRegistry->getConfigurators());
        $this->assertTrue($configuratorRegistry->has(ConfiguratorRegistryTest::class));
        $this->assertFalse($configuratorRegistry->has(BootstrapDummy::class));
        $this->assertInstanceOf(PackageDummy::class, $configuratorRegistry->get(ConfiguratorRegistryTest::class));
    }

    public function testGetConfiguratorException()
    {
        $configuratorRegistry = new ConfiguratorRegistry();

        $this->expectException(ArgumentNotFoundException::class);
        $configuratorRegistry->get(BootstrapDummy::class);
    }
}
