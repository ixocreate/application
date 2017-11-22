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
use KiwiSuite\Application\Bootstrap\MiddlewareBootstrap;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use PHPUnit\Framework\TestCase;

class MiddlewareBootstrapTest extends TestCase
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
        $middlewareBootstrap = new MiddlewareBootstrap();
        $bootstrapItemResult = $middlewareBootstrap->bootstrap($this->applicationConfig);

        $this->assertArrayHasKey('MiddlewareConfig', $bootstrapItemResult->getServices());
        $this->assertInstanceOf(ServiceManagerConfig::class, $bootstrapItemResult->getServices()['MiddlewareConfig']);

        /** @var ServiceManagerConfig $serviceManagerConfig */
        $serviceManagerConfig = $bootstrapItemResult->getServices()['MiddlewareConfig'];
        $this->assertArrayHasKey(\DateTime::class, $serviceManagerConfig->getFactories());
    }
}
