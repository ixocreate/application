<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\ServiceManager;

use Ixocreate\Application\ServiceManager\SubManagerConfig;
use Ixocreate\Application\ServiceManager\SubManagerConfigurator;
use Ixocreate\Misc\Application\Scan\AbstractClass;
use Ixocreate\Misc\Application\Scan\Class1;
use Ixocreate\Misc\Application\Scan\Class2;
use Ixocreate\Misc\Application\Scan\Class4;
use Ixocreate\Misc\Application\Scan\SubDir\Class3;
use Ixocreate\Misc\Application\Scan\TestInterface;
use Ixocreate\Misc\Application\ServiceManager\DateTimeFactory;
use Ixocreate\Misc\Application\ServiceManager\DelegatorFactory;
use Ixocreate\Misc\Application\ServiceManager\Initializer;
use Ixocreate\Misc\Application\ServiceManager\SubManager;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Application\ServiceManager\AbstractServiceManagerConfigurator
 * @covers \Ixocreate\Application\ServiceManager\SubManagerConfigurator
 */
class SubManagerConfiguratorTest extends TestCase
{
    public function testEmptyValidation()
    {
        $configurator = new SubManagerConfigurator(SubManager::class);
        $this->assertNull($configurator->getValidation());
    }

    public function testDirectoryScan()
    {
        $serviceManagerConfigurator = new SubManagerConfigurator(SubManager::class, TestInterface::class);
        $serviceManagerConfigurator->addDirectory(__DIR__ . '/../../misc/Scan', true, [AbstractClass::class]);
        $serviceManagerConfig = $serviceManagerConfigurator->getServiceManagerConfig();
        $this->assertArrayNotHasKey(Class1::class, $serviceManagerConfig->getFactories());
        $this->assertArrayHasKey(Class2::class, $serviceManagerConfig->getFactories());
        $this->assertArrayHasKey(Class3::class, $serviceManagerConfig->getFactories());
        $this->assertArrayHasKey(Class4::class, $serviceManagerConfig->getFactories());
    }

    public function testGetServiceManagerConfig()
    {
        $serviceManagerConfigurator = new SubManagerConfigurator(SubManager::class, \DateTime::class);
        $serviceManagerConfigurator->addFactory('factory', DateTimeFactory::class);
        $serviceManagerConfigurator->addInitializer(Initializer::class);
        $serviceManagerConfigurator->addDelegator('test', [DelegatorFactory::class]);
        $serviceManagerConfigurator->addLazyService(\DateTime::class);

        $serviceManagerConfig = $serviceManagerConfigurator->getServiceManagerConfig();

        $this->assertInstanceOf(SubManagerConfig::class, $serviceManagerConfig);

        $this->assertEquals($serviceManagerConfigurator->getSubManagerClass(), $serviceManagerConfig->getSubManagerName());
        $this->assertEquals($serviceManagerConfigurator->getValidation(), $serviceManagerConfig->getValidation());
        $this->assertEquals($serviceManagerConfigurator->getFactories(), $serviceManagerConfig->getFactories());
        $this->assertEquals($serviceManagerConfigurator->getInitializers(), $serviceManagerConfig->getInitializers());
        $this->assertEquals($serviceManagerConfigurator->getDelegators(), $serviceManagerConfig->getDelegators());
        $this->assertEquals($serviceManagerConfigurator->getLazyServices(), $serviceManagerConfig->getLazyServices());
    }
}
