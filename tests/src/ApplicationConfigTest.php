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

use KiwiSuite\Application\ApplicationConfig;
use KiwiSuite\Application\Bootstrap\ServiceManagerBootstrap;
use KiwiSuite\Application\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ApplicationConfigTest extends TestCase
{
    public function testDefaults()
    {
        $applicationConfig = new ApplicationConfig([]);

        $this->assertSame(true, $applicationConfig->isDevelopment());
        $this->assertSame("resources/application/", $applicationConfig->getPersistCacheDirectory());
        $this->assertSame([], $applicationConfig->getBootstrapQueue());
        $this->assertSame("bootstrap/", $applicationConfig->getBootstrapDirectory());
        $this->assertSame("data/cache/application/", $applicationConfig->getCacheDirectory());
    }

    public function testDevelopment()
    {
        $applicationConfig = new ApplicationConfig([
            'development' => true,
        ]);
        $this->assertTrue($applicationConfig->isDevelopment());

        $applicationConfig = new ApplicationConfig([
            'development' => false,
        ]);
        $this->assertFalse($applicationConfig->isDevelopment());

        $this->expectException(InvalidArgumentException::class);
        new ApplicationConfig([
            'development' => "string",
        ]);
    }

    public function testPersistCacheDirectory()
    {
        $applicationConfig = new ApplicationConfig([
            'persistCacheDirectory' => 'testDirectory',
        ]);
        $this->assertSame("testDirectory/", $applicationConfig->getPersistCacheDirectory());

        $applicationConfig = new ApplicationConfig([
            'persistCacheDirectory' => "testDirectory/",
        ]);
        $this->assertSame("testDirectory/", $applicationConfig->getPersistCacheDirectory());

        $applicationConfig = new ApplicationConfig([
            'persistCacheDirectory' => "",
        ]);
        $this->assertSame("./", $applicationConfig->getPersistCacheDirectory());

        $this->expectException(InvalidArgumentException::class);
        new ApplicationConfig([
            'persistCacheDirectory' => [],
        ]);
    }

    public function testBootstrapDirectory()
    {
        $applicationConfig = new ApplicationConfig([
            'bootstrapDirectory' => 'testDirectory',
        ]);
        $this->assertSame("testDirectory/", $applicationConfig->getBootstrapDirectory());

        $applicationConfig = new ApplicationConfig([
            'bootstrapDirectory' => "testDirectory/",
        ]);
        $this->assertSame("testDirectory/", $applicationConfig->getBootstrapDirectory());

        $applicationConfig = new ApplicationConfig([
            'bootstrapDirectory' => "",
        ]);
        $this->assertSame("./", $applicationConfig->getBootstrapDirectory());

        $this->expectException(InvalidArgumentException::class);
        new ApplicationConfig([
            'bootstrapDirectory' => [],
        ]);
    }

    public function testCacheDirectory()
    {
        $applicationConfig = new ApplicationConfig([
            'cacheDirectory' => 'testDirectory',
        ]);
        $this->assertSame("testDirectory/", $applicationConfig->getCacheDirectory());

        $applicationConfig = new ApplicationConfig([
            'cacheDirectory' => "testDirectory/",
        ]);
        $this->assertSame("testDirectory/", $applicationConfig->getCacheDirectory());

        $applicationConfig = new ApplicationConfig([
            'cacheDirectory' => "",
        ]);
        $this->assertSame("./", $applicationConfig->getCacheDirectory());

        $this->expectException(InvalidArgumentException::class);
        new ApplicationConfig([
            'cacheDirectory' => [],
        ]);
    }

    public function testBootstrapQueue()
    {
        $applicationConfig = new ApplicationConfig([
            'bootstrapQueue' => [ServiceManagerBootstrap::class],
        ]);
        $this->assertSame([ServiceManagerBootstrap::class], $applicationConfig->getBootstrapQueue());

        $applicationConfig = new ApplicationConfig([
            'bootstrapQueue' => ["test" => ServiceManagerBootstrap::class],
        ]);
        $this->assertSame([ServiceManagerBootstrap::class], $applicationConfig->getBootstrapQueue());

        $this->expectException(InvalidArgumentException::class);
        new ApplicationConfig([
            'bootstrapQueue' => "string",
        ]);
    }

    public function testSerialize()
    {
        $config = [
            'development'                   => false,
            'persistCacheDirectory'         => 'resources/application_test/',
            'cacheDirectory'                => 'data/cache/application_test/',
            'bootstrapDirectory'            => 'bootstrap_test/',
            'bootstrapQueue'                => [ServiceManagerBootstrap::class],
        ];

        $applicationConfig = new ApplicationConfig($config);

        $this->assertSame(\serialize($config), $applicationConfig->serialize());
    }

    public function testUnserialize()
    {
        $config = [
            'development'                   => false,
            'persistCacheDirectory'         => 'resources/application_test/',
            'cacheDirectory'                => 'data/cache/application_test/',
            'bootstrapDirectory'            => 'bootstrap_test/',
            'bootstrapQueue'                => [ServiceManagerBootstrap::class],
        ];

        $applicationConfig = new ApplicationConfig([]);
        $applicationConfig->unserialize(\serialize($config));

        $this->assertSame($config['development'], $applicationConfig->isDevelopment());
        $this->assertSame($config['persistCacheDirectory'], $applicationConfig->getPersistCacheDirectory());
        $this->assertSame($config['cacheDirectory'], $applicationConfig->getCacheDirectory());
        $this->assertSame($config['bootstrapDirectory'], $applicationConfig->getBootstrapDirectory());
        $this->assertSame($config['bootstrapQueue'], $applicationConfig->getBootstrapQueue());
    }
}
