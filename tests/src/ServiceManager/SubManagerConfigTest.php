<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateTest\ServiceManager;

use Ixocreate\Application\ServiceManager\SubManagerConfig;
use Ixocreate\Application\ServiceManager\SubManagerConfigurator;
use Ixocreate\Misc\Application\Scan\Class1;
use Ixocreate\Misc\Application\Scan\Class2;
use Ixocreate\Misc\Application\ServiceManager\DateTimeFactory;
use Ixocreate\Misc\Application\ServiceManager\DelegatorFactory;
use Ixocreate\Misc\Application\ServiceManager\Initializer;
use Ixocreate\Misc\Application\ServiceManager\SubManager;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Application\ServiceManager\SubManagerConfig
 */
class SubManagerConfigTest extends TestCase
{
    public function testNamedServices()
    {
        $configurator = new SubManagerConfigurator(SubManager::class);
        $configurator->addFactory(Class1::class);
        $configurator->addFactory(Class2::class);

        $config = new SubManagerConfig($configurator);

        $this->assertEquals(['class1' => Class1::class, 'class2' => Class2::class], $config->getNamedServices());
    }

    public function testGetFactories()
    {
        $factories = [];
        $SubManagerConfig = new SubManagerConfig(new SubManagerConfigurator(SubManager::class));
        $this->assertEquals($factories, $SubManagerConfig->getFactories());

        $factories = [
            'test' => DateTimeFactory::class,
        ];
        $configurator = new SubManagerConfigurator(SubManager::class);
        foreach ($factories as $key => $value) {
            $configurator->addService($key, $value);
        }
        $this->assertEquals($factories, $configurator->getServiceManagerConfig()->getFactories());
    }

    public function testDelegators()
    {
        $delegators = [];
        $SubManagerConfig = new SubManagerConfig(new SubManagerConfigurator(SubManager::class));
        $this->assertEquals($delegators, $SubManagerConfig->getDelegators());

        $delegators = [
            'test' => [DelegatorFactory::class],
        ];
        $configurator = new SubManagerConfigurator(SubManager::class);
        foreach ($delegators as $key => $value) {
            $configurator->addDelegator($key, $value);
        }
        $this->assertEquals($delegators, $configurator->getServiceManagerConfig()->getDelegators());
    }

    public function testGetLazyServices()
    {
        $lazyServices = [];
        $SubManagerConfig = new SubManagerConfig(new SubManagerConfigurator(SubManager::class));
        $this->assertEquals($lazyServices, $SubManagerConfig->getLazyServices());

        $lazyServices = [
            'test' => \DateTime::class,
        ];
        $configurator = new SubManagerConfigurator(SubManager::class);
        foreach ($lazyServices as $key => $value) {
            $configurator->addLazyService($key, $value);
        }
        $this->assertEquals($lazyServices, $configurator->getServiceManagerConfig()->getLazyServices());
    }

    public function testGetInitializers()
    {
        $initializers = [];
        $SubManagerConfig = new SubManagerConfig(new SubManagerConfigurator(SubManager::class));
        $this->assertEquals($initializers, $SubManagerConfig->getInitializers());

        $initializers = [
            Initializer::class,
        ];
        $configurator = new SubManagerConfigurator(SubManager::class);
        foreach ($initializers as $key => $value) {
            $configurator->addInitializer($value);
        }
        $this->assertEquals($initializers, $configurator->getServiceManagerConfig()->getInitializers());
    }

    public function testSerialize()
    {
        $configurator = new SubManagerConfigurator(SubManager::class);
        $configurator->addService('test', DateTimeFactory::class);

        $config = new SubManagerConfig($configurator);

        $this->assertEquals(\serialize([
            'factories' => [
                'test' => DateTimeFactory::class,
            ],
            'delegators' => [],
            'lazyServices' => [],
            'initializers' => [],
            'validation' => null,
            'namedServices' => [],
        ]), $config->serialize());
    }

    public function testUnserialize()
    {
        $items = [
            'factories' => [
                'test' => DateTimeFactory::class,
            ],
        ];
        $configurator = new SubManagerConfigurator(SubManager::class);
        $configurator->addService('test', DateTimeFactory::class);

        $config = new SubManagerConfig($configurator);
        $config->unserialize(\serialize($items));
        $this->assertEquals($items['factories'], $config->getFactories());
    }
}
