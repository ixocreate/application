<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\ServiceManager;

use Ixocreate\Application\Service\ServiceRegistry;
use Ixocreate\Application\ServiceManager\ServiceManagerConfig;
use Ixocreate\Application\ServiceManager\ServiceManagerConfigurator;
use Ixocreate\Application\ServiceManager\SubManagerFactory;
use Ixocreate\Misc\Application\Scan\AbstractClass;
use Ixocreate\Misc\Application\Scan\AnotherClass;
use Ixocreate\Misc\Application\Scan\Class1;
use Ixocreate\Misc\Application\Scan\Class2;
use Ixocreate\Misc\Application\Scan\Class4;
use Ixocreate\Misc\Application\Scan\SubDir\Class3;
use Ixocreate\Misc\Application\Scan\TestInterface;
use Ixocreate\Misc\Application\ServiceManager\DateTimeFactory;
use Ixocreate\Misc\Application\ServiceManager\DateTimeManager;
use Ixocreate\Misc\Application\ServiceManager\DateTimeManagerFactory;
use Ixocreate\Misc\Application\ServiceManager\DelegatorFactory;
use Ixocreate\Misc\Application\ServiceManager\DelegatorTwoFactory;
use Ixocreate\Misc\Application\ServiceManager\Initializer;
use Ixocreate\Misc\Application\ServiceManager\InitializerTwo;
use Ixocreate\Misc\Application\ServiceManager\LazyLoadingObject;
use Ixocreate\Misc\Application\ServiceManager\SubManager;
use Ixocreate\ServiceManager\Exception\InvalidArgumentException;
use Ixocreate\ServiceManager\Factory\AutowireFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Application\ServiceManager\AbstractServiceManagerConfigurator
 * @covers \Ixocreate\Application\ServiceManager\ServiceManagerConfigurator
 */
class ServiceManagerConfiguratorTest extends TestCase
{
    public function testInvalidAutowireFactory()
    {
        $this->expectException(InvalidArgumentException::class);
        new ServiceManagerConfigurator(\DateTime::class);
    }

    public function testFactories()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $factories = [
            'dateTime' => DateTimeFactory::class,
            'testAutoWire' => null,
        ];

        foreach ($factories as $name => $value) {
            if (empty($value)) {
                $serviceManagerConfigurator->addFactory($name);
                continue;
            }
            $serviceManagerConfigurator->addFactory($name, $value);
        }

        $factories['testAutoWire'] = AutowireFactory::class;

        $this->assertEquals($factories, $serviceManagerConfigurator->getFactories());
    }

    public function testNotExistingFactory()
    {
        $this->expectException(InvalidArgumentException::class);

        $configurator = new ServiceManagerConfigurator();
        $configurator->addFactory(\DateTime::class, 'ClassDoesNotExist');
    }

    public function testInvalidFactory()
    {
        $this->expectException(InvalidArgumentException::class);

        $configurator = new ServiceManagerConfigurator();
        $configurator->addFactory(\DateTime::class, \DateTime::class);
    }

    public function testServices()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $factories = [
            'dateTime' => DateTimeFactory::class,
        ];

        $serviceManagerConfigurator->addService('dateTime', DateTimeFactory::class);

        $this->assertEquals($factories, $serviceManagerConfigurator->getFactories());
    }

    public function testDelegators()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $delegators1 = [
            'test' => [DelegatorFactory::class],
        ];

        foreach ($delegators1 as $name => $value) {
            $serviceManagerConfigurator->addDelegator($name, $value);
        }

        $this->assertEquals($delegators1, $serviceManagerConfigurator->getDelegators());

        $delegators2 = [
            'test2' => [],
            'test' => [DelegatorTwoFactory::class],
        ];

        foreach ($delegators2 as $name => $value) {
            $serviceManagerConfigurator->addDelegator($name, $value);
        }

        $this->assertEquals($delegators1 + $delegators2, $serviceManagerConfigurator->getDelegators());
    }

    public function testInvalidDelegator()
    {
        $this->expectException(InvalidArgumentException::class);

        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addDelegator('service', [123456]);
    }

    public function testInvalidDelegatorFactory()
    {
        $this->expectException(InvalidArgumentException::class);

        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addDelegator('service', [\DateTime::class]);
    }

    public function testLazyServices()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $lazyServices = [
            'dateTime' => DateTimeFactory::class,
            LazyLoadingObject::class => null,
        ];

        foreach ($lazyServices as $name => $value) {
            if (empty($value)) {
                $serviceManagerConfigurator->addLazyService($name);
                continue;
            }
            $serviceManagerConfigurator->addLazyService($name, $value);
        }

        $lazyServices[LazyLoadingObject::class] = LazyLoadingObject::class;

        $this->assertEquals([
            'dateTime' => DateTimeFactory::class,
            LazyLoadingObject::class => LazyLoadingObject::class,
        ], $serviceManagerConfigurator->getLazyServices());
    }

    public function testInvalidLazyService()
    {
        $this->expectException(InvalidArgumentException::class);

        $configurator = new ServiceManagerConfigurator();
        $configurator->addLazyService('ClassDoesNotExist', 'ClassDoesNotExist');
    }

    public function testInitializer()
    {
        $initializer = [
            Initializer::class,
            InitializerTwo::class,
        ];

        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        foreach ($initializer as $value) {
            $serviceManagerConfigurator->addInitializer($value);
        }

        $this->assertEquals($initializer, $serviceManagerConfigurator->getInitializers());
    }

    public function testInvalidInitializer()
    {
        $this->expectException(InvalidArgumentException::class);

        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addInitializer(DateTimeFactory::class);
    }

    public function testSubManagers()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $subManagers = [
            SubManager::class => null,
            DateTimeManager::class => DateTimeManagerFactory::class,
        ];

        foreach ($subManagers as $name => $value) {
            if ($value !== null) {
                $serviceManagerConfigurator->addSubManager($name, $value);
            } else {
                $serviceManagerConfigurator->addSubManager($name);
            }
        }

        $subManagers[SubManager::class] = SubManagerFactory::class;

        $this->assertEquals($subManagers, $serviceManagerConfigurator->getSubManagers());
    }

    public function testInvalidSubManager()
    {
        $this->expectException(InvalidArgumentException::class);

        $configurator = new ServiceManagerConfigurator();
        $configurator->addSubManager(\DateTime::class);
    }

    public function testNotExistingSubManager()
    {
        $this->expectException(InvalidArgumentException::class);

        $configurator = new ServiceManagerConfigurator();
        $configurator->addSubManager(SubManager::class, 'ClassDoesNotExist');
    }

    public function testInvalidSubManagerFactory()
    {
        $this->expectException(InvalidArgumentException::class);

        $configurator = new ServiceManagerConfigurator();
        $configurator->addSubManager(SubManager::class, \DateTime::class);
    }

    public function testDirectories()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $serviceManagerConfigurator->addDirectory('test');
        $serviceManagerConfigurator->addDirectory('test2/', false);

        $directories = [
            'test/' => [
                'dir' => 'test/',
                'recursive' => true,
                'only' => [],
            ],
            'test2/' => [
                'dir' => 'test2/',
                'recursive' => false,
                'only' => [],
            ],
        ];

        $this->assertEquals($directories, $serviceManagerConfigurator->getDirectories());
    }

    public function testDirectoryScan()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $serviceManagerConfigurator->addFactory(AnotherClass::class, DateTimeFactory::class);
        $serviceManagerConfigurator->addDirectory(__DIR__ . '/../../misc/Scan');
        $serviceManagerConfigurator->addDirectory(__DIR__ . '/../../misc/doesnt_exist');
        $serviceManagerConfig = $serviceManagerConfigurator->getServiceManagerConfig();

        $factories = $serviceManagerConfig->getFactories();
        $this->assertArrayHasKey(AnotherClass::class, $factories);
        $this->assertEquals(DateTimeFactory::class, $factories[AnotherClass::class]);
        $this->assertArrayHasKey(Class1::class, $factories);
        $this->assertArrayHasKey(Class2::class, $factories);
        $this->assertArrayHasKey(Class3::class, $factories);
        $this->assertArrayNotHasKey(AbstractClass::class, $factories);
        $this->assertArrayNotHasKey(TestInterface::class, $factories);
        $this->assertArrayNotHasKey('testfile', $factories);

        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addDirectory(__DIR__ . '/../../misc/Scan', false);
        $serviceManagerConfig = $serviceManagerConfigurator->getServiceManagerConfig();
        $this->assertArrayHasKey(Class1::class, $serviceManagerConfig->getFactories());
        $this->assertArrayHasKey(Class2::class, $serviceManagerConfig->getFactories());
        $this->assertArrayNotHasKey(Class3::class, $serviceManagerConfig->getFactories());

        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addDirectory(__DIR__ . '/../../misc/Scan', true, [AbstractClass::class, TestInterface::class]);
        $serviceManagerConfig = $serviceManagerConfigurator->getServiceManagerConfig();
        $this->assertArrayNotHasKey(Class1::class, $serviceManagerConfig->getFactories());
        $this->assertArrayHasKey(Class2::class, $serviceManagerConfig->getFactories());
        $this->assertArrayHasKey(Class3::class, $serviceManagerConfig->getFactories());
        $this->assertArrayHasKey(Class4::class, $serviceManagerConfig->getFactories());
    }

    public function testGetServiceManagerConfig()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addInitializer(Initializer::class);
        $serviceManagerConfigurator->addSubManager(DateTimeManager::class, DateTimeManagerFactory::class);
        $serviceManagerConfigurator->addLazyService(\DateTime::class);
        $serviceManagerConfigurator->addDelegator('test', [DelegatorFactory::class]);
        $serviceManagerConfigurator->addFactory('factory', DateTimeFactory::class);

        $serviceManagerConfig = $serviceManagerConfigurator->getServiceManagerConfig();

        $this->assertInstanceOf(ServiceManagerConfig::class, $serviceManagerConfig);

        $this->assertEquals($serviceManagerConfigurator->getInitializers(), $serviceManagerConfig->getInitializers());
        $this->assertEquals($serviceManagerConfigurator->getFactories(), $serviceManagerConfig->getFactories());
        $this->assertEquals($serviceManagerConfigurator->getSubManagers(), $serviceManagerConfig->getSubManagers());
        $this->assertEquals($serviceManagerConfigurator->getLazyServices(), $serviceManagerConfig->getLazyServices());
        $this->assertEquals($serviceManagerConfigurator->getDelegators(), $serviceManagerConfig->getDelegators());
    }

    public function testRegisterService()
    {
        $configurator = new ServiceManagerConfigurator();
        $registry = new ServiceRegistry();

        $configurator->registerService($registry);

        $this->assertInstanceOf(ServiceManagerConfig::class, $registry->get(ServiceManagerConfig::class));
    }
}
