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
namespace KiwiSuiteTest\Application;

use KiwiSuite\Application\Bootstrap;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuiteMisc\Application\ApplicationTest;
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $bootstrap = new Bootstrap();
        $serviceManager = $bootstrap->bootstrap(__DIR__ . '/../bootstrap', new ApplicationTest());

        $this->assertInstanceOf(ServiceManager::class, $serviceManager);
        $this->assertArrayHasKey(\DateTime::class, $serviceManager->getServiceManagerConfig()->getFactories());
        $this->assertArrayHasKey(\DateTimeZone::class, $serviceManager->getServiceManagerConfig()->getFactories());
        $this->assertArrayHasKey(\DateInterval::class, $serviceManager->getServiceManagerConfig()->getFactories());
        $this->assertArrayHasKey(\DatePeriod::class, $serviceManager->getServiceManagerConfig()->getFactories());
    }
}
