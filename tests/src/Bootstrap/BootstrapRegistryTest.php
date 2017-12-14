<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteTest\Application\Bootstrap;

use KiwiSuite\Application\Bootstrap\BootstrapRegistry;
use KiwiSuite\Application\Exception\ArgumentNotFoundException;
use KiwiSuiteMisc\Application\BootstrapTest;
use KiwiSuiteMisc\Application\ModuleTest;
use PHPUnit\Framework\TestCase;

class BootstrapRegistryTest extends TestCase
{
    public function testModules()
    {
        $bootstrapRegistry = new BootstrapRegistry([ModuleTest::class]);
        $this->assertSame([ModuleTest::class], $bootstrapRegistry->getModules());
    }

    public function testServices()
    {
        $bootstrapRegistry = new BootstrapRegistry([]);
        $bootstrapRegistry->addService(ModuleTest::class, new ModuleTest());
        $this->assertArrayHasKey(ModuleTest::class, $bootstrapRegistry->getServices());
        $this->assertTrue($bootstrapRegistry->hasService(ModuleTest::class));
        $this->assertFalse($bootstrapRegistry->hasService(BootstrapTest::class));
        $this->assertInstanceOf(ModuleTest::class, $bootstrapRegistry->getService(ModuleTest::class));

        $this->expectException(ArgumentNotFoundException::class);
        $bootstrapRegistry->getService(BootstrapTest::class);
    }

    public function testRegistry()
    {
        $bootstrapRegistry = new BootstrapRegistry([]);
        $bootstrapRegistry->add(ModuleTest::class, new ModuleTest());
        $this->assertArrayHasKey(ModuleTest::class, $bootstrapRegistry->getRegistry());
        $this->assertTrue($bootstrapRegistry->has(ModuleTest::class));
        $this->assertFalse($bootstrapRegistry->has(BootstrapTest::class));
        $this->assertInstanceOf(ModuleTest::class, $bootstrapRegistry->get(ModuleTest::class));

        $this->expectException(ArgumentNotFoundException::class);
        $bootstrapRegistry->get(BootstrapTest::class);
    }
}
