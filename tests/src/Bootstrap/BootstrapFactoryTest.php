<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Bootstrap;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Bootstrap\BootstrapFactory;
use Ixocreate\Application\Console\ConsoleBootstrapItem;
use Ixocreate\Application\Http\Middleware\MiddlewareBootstrapItem;
use Ixocreate\Application\Http\Pipe\PipeBootstrapItem;
use Ixocreate\Application\Publish\PublishBootstrapItem;
use Ixocreate\Application\Service\ServiceHandler;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Application\Uri\ApplicationUriBootstrapItem;
use Ixocreate\ServiceManager\ServiceManagerConfigInterface;
use PHPUnit\Framework\TestCase;

/** @covers \Ixocreate\Application\Bootstrap\BootstrapFactory */
class BootstrapFactoryTest extends TestCase
{
    public function testCreateApplicationConfigurator()
    {
        $factory = new BootstrapFactory();

        $directory = '/foo/bar/directory/';
        $configurator = $factory->createApplicationConfigurator($directory);

        $this->assertEquals($directory, $configurator->getBootstrapDirectory());

        $bootstrapItems = $configurator->getBootstrapItems();
        $this->assertCount(5, $bootstrapItems);
        $this->assertContains(ApplicationUriBootstrapItem::class, $bootstrapItems);
        $this->assertContains(ConsoleBootstrapItem::class, $bootstrapItems);
        $this->assertContains(MiddlewareBootstrapItem::class, $bootstrapItems);
        $this->assertContains(PipeBootstrapItem::class, $bootstrapItems);
        $this->assertContains(PublishBootstrapItem::class, $bootstrapItems);
    }

    public function testCreateServiceHandler()
    {
        $factory = new BootstrapFactory();
        $this->assertInstanceOf(ServiceHandler::class, $factory->createServiceHandler());
    }

    public function testCreateServiceManager()
    {
        $factory = new BootstrapFactory();

        $smConfig = $this->createMock(ServiceManagerConfigInterface::class);
        $appConfig = $this->createMock(ApplicationConfig::class);
        $appConfig->method('getPersistCacheDirectory')->willReturn('/some/directory/');
        $appConfig->method('isDevelopment')->willReturn(true);
        $registry = $this->createMock(ServiceRegistryInterface::class);
        $registry->method('all')->willReturn([
            'testDate' => new \DateTime(),
        ]);

        $sm = $factory->createServiceManager($smConfig, $appConfig, $registry);

        $this->assertEquals($smConfig, $sm->serviceManagerConfig());
        $this->assertEquals(false, $sm->serviceManagerSetup()->isPersistLazyLoading());
        $this->assertEquals(false, $sm->serviceManagerSetup()->isPersistAutowire());
        $this->assertTrue($sm->has('testDate'));
    }
}
