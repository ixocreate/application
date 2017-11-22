<?php
namespace KiwiSuiteTest\Application\Http\Middleware\Factory;

use KiwiSuite\Application\Http\Middleware\Factory\MiddlewareSubManagerFactory;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerSetup;
use KiwiSuite\ServiceManager\SubManager\SubManager;
use PHPUnit\Framework\TestCase;

class MiddlewareSubManagerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $container = new ServiceManager(
            new ServiceManagerConfig([]),
            new ServiceManagerSetup(),
            [
                'MiddlewareConfig' => new ServiceManagerConfig([])
            ]
        );

        $factory = new MiddlewareSubManagerFactory();

        $this->assertInstanceOf(SubManager::class, $factory->__invoke($container, 'MiddlewareSubManager'));
    }
}
