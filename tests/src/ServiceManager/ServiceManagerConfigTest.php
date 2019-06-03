<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateTest\ServiceManager;

use Ixocreate\Application\ServiceManager\ServiceManagerConfig;
use Ixocreate\Application\ServiceManager\ServiceManagerConfigurator;
use Ixocreate\Misc\Application\Scan\Class1;
use Ixocreate\Misc\Application\Scan\Class2;
use Ixocreate\Misc\Application\ServiceManager\DateTimeFactory;
use Ixocreate\Misc\Application\ServiceManager\DateTimeManager;
use Ixocreate\Misc\Application\ServiceManager\DateTimeManagerFactory;
use Ixocreate\Misc\Application\ServiceManager\DelegatorFactory;
use Ixocreate\Misc\Application\ServiceManager\Initializer;
use PHPUnit\Framework\TestCase;

/**
 * Class ServiceManagerConfigTest
 * @package IxocreateTest\ServiceManager
 *
 * @covers \Ixocreate\Application\ServiceManager\ServiceManagerConfig
 */
class ServiceManagerConfigTest extends TestCase
{
    public function testNamedServices()
    {
        $configurator = new ServiceManagerConfigurator();
        $configurator->addFactory(Class1::class);
        $configurator->addFactory(Class2::class);

        $serviceManagerConfig = new ServiceManagerConfig($configurator);

        $this->assertEquals(['class1' => Class1::class, 'class2' => Class2::class], $serviceManagerConfig->getNamedServices());
    }

    public function testGetFactories()
    {
        $factories = [];
        $serviceManagerConfig = new ServiceManagerConfig(new ServiceManagerConfigurator());
        $this->assertEquals($factories, $serviceManagerConfig->getFactories());

        $factories = [
            'test' => DateTimeFactory::class,
        ];
        $serviceManagerConfigigurator = new ServiceManagerConfigurator();
        foreach ($factories as $key => $value) {
            $serviceManagerConfigigurator->addService($key, $value);
        }
        $this->assertEquals($factories, $serviceManagerConfigigurator->getServiceManagerConfig()->getFactories());
    }

    public function testGetSubManagers()
    {
        $subManagers = [];
        $serviceManagerConfig = new ServiceManagerConfig(new ServiceManagerConfigurator());
        $this->assertEquals($subManagers, $serviceManagerConfig->getSubManagers());

        $subManagers = [
            DateTimeManager::class => DateTimeManagerFactory::class,
        ];
        $serviceManagerConfigigurator = new ServiceManagerConfigurator();
        foreach ($subManagers as $key => $value) {
            $serviceManagerConfigigurator->addSubManager($key, $value);
        }
        $this->assertEquals($subManagers, $serviceManagerConfigigurator->getServiceManagerConfig()->getSubManagers());
    }

    public function testDelegators()
    {
        $delegators = [];
        $serviceManagerConfig = new ServiceManagerConfig(new ServiceManagerConfigurator());
        $this->assertEquals($delegators, $serviceManagerConfig->getDelegators());

        $delegators = [
            'test' => [DelegatorFactory::class],
        ];
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        foreach ($delegators as $key => $value) {
            $serviceManagerConfigurator->addDelegator($key, $value);
        }
        $this->assertEquals($delegators, $serviceManagerConfigurator->getServiceManagerConfig()->getDelegators());
    }

    public function testGetLazyServices()
    {
        $lazyServices = [];
        $serviceManagerConfig = new ServiceManagerConfig(new ServiceManagerConfigurator());
        $this->assertEquals($lazyServices, $serviceManagerConfig->getLazyServices());

        $lazyServices = [
            'test' => \DateTime::class,
        ];
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        foreach ($lazyServices as $key => $value) {
            $serviceManagerConfigurator->addLazyService($key, $value);
        }
        $this->assertEquals($lazyServices, $serviceManagerConfigurator->getServiceManagerConfig()->getLazyServices());
    }

    public function testGetInitializers()
    {
        $initializers = [];
        $serviceManagerConfig = new ServiceManagerConfig(new ServiceManagerConfigurator());
        $this->assertEquals($initializers, $serviceManagerConfig->getInitializers());

        $initializers = [
            Initializer::class,
        ];
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        foreach ($initializers as $key => $value) {
            $serviceManagerConfigurator->addInitializer($value);
        }
        $this->assertEquals($initializers, $serviceManagerConfigurator->getServiceManagerConfig()->getInitializers());
    }

    public function testSerialize()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addService('test', DateTimeFactory::class);

        $serviceManagerConfig = new ServiceManagerConfig($serviceManagerConfigurator);

        $this->assertEquals(\serialize([
            'factories' => [
                'test' => DateTimeFactory::class,
            ],
            'delegators' => [],
            'lazyServices' => [],
            'initializers' => [],
            'subManagers' => [],
            'namedServices' => [],
        ]), $serviceManagerConfig->serialize());
    }

    public function testUnserialize()
    {
        $items = [
            'factories' => [
                'test' => DateTimeFactory::class,
            ],
        ];
        $serviceManagerConfigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigurator->addService('test', DateTimeFactory::class);

        $serviceManagerConfig = new ServiceManagerConfig($serviceManagerConfigurator);
        $serviceManagerConfig->unserialize(\serialize($items));
        $this->assertEquals($items['factories'], $serviceManagerConfig->getFactories());
    }
}
