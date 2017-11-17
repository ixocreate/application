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

use KiwiSuite\Application\HttpApplication;
use PHPUnit\Framework\TestCase;

class HttpApplicationTest extends TestCase
{
    public function testRun()
    {
        $httpApplication = new HttpApplication(__DIR__ . "/../bootstrap");
        $httpApplication->run();

        $this->expectOutputString("test");
    }
}
