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
namespace KiwiSuiteTest\Application\Bootstrap;

use KiwiSuite\Application\ApplicationConfig;
use KiwiSuite\Application\Bootstrap\ServiceManagerBootstrap;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use PHPUnit\Framework\TestCase;

class ServiceManagerBootstrapTest extends TestCase
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    public function setUp()
    {
        $this->applicationConfig = new ApplicationConfig([
            'bootstrapDirectory' => __DIR__ . '/../../bootstrap',
        ]);
    }

    public function testBootstrap()
    {
        $serviceManagerBootstrap = new ServiceManagerBootstrap();
        $bootstrapItemResult = $serviceManagerBootstrap->bootstrap($this->applicationConfig);

        $this->assertArrayHasKey(ServiceManagerConfig::class, $bootstrapItemResult->getServices());
        $this->assertInstanceOf(ServiceManagerConfig::class, $bootstrapItemResult->getServices()[ServiceManagerConfig::class]);

        /** @var ServiceManagerConfig $serviceManagerConfig */
        $serviceManagerConfig = $bootstrapItemResult->getServices()[ServiceManagerConfig::class];
        $this->assertArrayHasKey(\DateTime::class, $serviceManagerConfig->getFactories());
    }
}
