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
use KiwiSuite\Application\Bootstrap\RouteBootstrap;
use KiwiSuite\Application\Http\Route\RouteConfig;
use PHPUnit\Framework\TestCase;

class RouteBootstrapTest extends TestCase
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
        $routeBootstrap = new RouteBootstrap();
        $bootstrapItemResult = $routeBootstrap->bootstrap($this->applicationConfig);

        $this->assertArrayHasKey(RouteConfig::class, $bootstrapItemResult->getHelpers());
        $this->assertInstanceOf(RouteConfig::class, $bootstrapItemResult->getHelpers()[RouteConfig::class]);

        //TODO check include
    }
}
