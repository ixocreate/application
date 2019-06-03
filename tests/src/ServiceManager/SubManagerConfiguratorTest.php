<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\ServiceManager;

use Ixocreate\Application\ServiceManager\ServiceManagerConfig;
use Ixocreate\Application\ServiceManager\ServiceManagerConfigurator;
use Ixocreate\Application\ServiceManager\SubManagerConfig;
use Ixocreate\Application\ServiceManager\SubManagerConfigurator;
use Ixocreate\Misc\Application\ServiceManager\DateTimeManager;
use Ixocreate\Misc\Application\ServiceManager\DateTimeManagerFactory;
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


    public function testServices()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        $factories = [
            'dateTime' => DateTimeFactory::class,
        ];

        $serviceManagerConfigurator->addService('dateTime', DateTimeFactory::class);

        $this->assertEquals($factories, $serviceManagerConfigurator->getFactories());
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

    public function testGetServiceManagerConfig()
    {
        $serviceManagerConfigurator = new SubManagerConfigurator(SubManager::class, \DateTime::class);
        $serviceManagerConfigurator->addInitializer(Initializer::class);
        $serviceManagerConfigurator->addLazyService(\DateTime::class);
        $serviceManagerConfigurator->addDelegator('test', [DelegatorFactory::class]);
        $serviceManagerConfigurator->addFactory('factory', DateTimeFactory::class);

        $serviceManagerConfig = $serviceManagerConfigurator->getServiceManagerConfig();

        $this->assertInstanceOf(SubManagerConfig::class, $serviceManagerConfig);

        $this->assertEquals($serviceManagerConfigurator->getInitializers(), $serviceManagerConfig->getInitializers());
        $this->assertEquals($serviceManagerConfigurator->getFactories(), $serviceManagerConfig->getFactories());
        $this->assertEquals($serviceManagerConfigurator->getLazyServices(), $serviceManagerConfig->getLazyServices());
        $this->assertEquals($serviceManagerConfigurator->getDelegators(), $serviceManagerConfig->getDelegators());
    }
}
