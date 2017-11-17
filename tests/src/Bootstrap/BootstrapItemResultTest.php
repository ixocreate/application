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
use KiwiSuite\Application\Bootstrap\BootstrapItemResult;
use PHPUnit\Framework\TestCase;

class BootstrapItemResultTest extends TestCase
{
    public function testGetServices()
    {
        $services = [
            ApplicationConfig::class => new ApplicationConfig([]),
        ];

        $bootstrapItemResult = new BootstrapItemResult($services);

        $this->assertSame($services, $bootstrapItemResult->getServices());
    }

    public function testHasServices()
    {
        $services = [
            ApplicationConfig::class => new ApplicationConfig([]),
        ];
        $bootstrapItemResult = new BootstrapItemResult($services);
        $this->assertTrue($bootstrapItemResult->hasServices());

        $services = [];
        $bootstrapItemResult = new BootstrapItemResult($services);
        $this->assertFalse($bootstrapItemResult->hasServices());
    }

    public function testGetHelpers()
    {
        $helpers = [
            ApplicationConfig::class => new ApplicationConfig([]),
        ];

        $bootstrapItemResult = new BootstrapItemResult([], $helpers);

        $this->assertSame($helpers, $bootstrapItemResult->getHelpers());
    }

    public function testHasHelpers()
    {
        $helpers = [
            ApplicationConfig::class => new ApplicationConfig([]),
        ];
        $bootstrapItemResult = new BootstrapItemResult([], $helpers);
        $this->assertTrue($bootstrapItemResult->hasHelpers());

        $bootstrapItemResult = new BootstrapItemResult([], []);
        $this->assertFalse($bootstrapItemResult->hasHelpers());
    }
}
