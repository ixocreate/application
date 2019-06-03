<?php

declare(strict_types=1);

namespace Ixocreate\Test\Application\ServiceManager;

use Ixocreate\Application\ServiceManager\ServiceManagerConfigurator;
use Ixocreate\Application\ServiceManager\SubManagerConfigurator;
use Ixocreate\Application\ServiceManager\SubManagerFactory;
use Ixocreate\Misc\Application\ServiceManager\SubManager;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use PHPUnit\Framework\TestCase;

class SubManagerFactoryTest extends TestCase
{
    public function testInvoce()
    {
        $factory = new SubManagerFactory();

        $configurator = new SubManagerConfigurator(SubManager::class);
        $config = $configurator->getServiceManagerConfig();

        $container = $this->createMock(ServiceManagerInterface::class);
        $container
            ->method('get')
            ->willReturnCallback(function($name) use ($config) {
                if ($name == SubManager::class . '::Config') {
                    return $config;
                }
            });

        $subManager = $factory($container, SubManager::class);

        $this->assertInstanceOf(SubManager::class, $subManager);
    }
}
