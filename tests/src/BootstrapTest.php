<?php
namespace KiwiSuiteTest\Application;

use KiwiSuite\Application\Bootstrap;
use KiwiSuite\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $bootstrap = new Bootstrap();
        $serviceManager = $bootstrap->bootstrap(__DIR__ . '/../bootstrap');

        $this->assertInstanceOf(ServiceManager::class, $serviceManager);
        $this->assertArrayHasKey(\DateTime::class, $serviceManager->getServiceManagerConfig()->getFactories());
        $this->assertArrayHasKey(\DateTimeZone::class, $serviceManager->getServiceManagerConfig()->getFactories());
        $this->assertArrayHasKey(\DateInterval::class, $serviceManager->getServiceManagerConfig()->getFactories());
    }
}
