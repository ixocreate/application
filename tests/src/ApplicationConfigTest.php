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
        $applicationConfig = new ApplicationConfig();

        $this->assertSame(true, $applicationConfig->isDevelopment());
        $this->assertSame("resources/application/", $applicationConfig->getPersistCacheDirectory());
        $this->assertSame([], $applicationConfig->getBootstrapQueue());
        $this->assertSame("bootstrap/", $applicationConfig->getBootstrapDirectory());
        $this->assertSame("config/", $applicationConfig->getConfigDirectory());
        $this->assertSame("data/cache/application/", $applicationConfig->getCacheDirectory());
    }

    public function testDevelopment()
    {
        $applicationConfig = new ApplicationConfig(true);
        $this->assertTrue($applicationConfig->isDevelopment());

        $applicationConfig = new ApplicationConfig(false);
        $this->assertFalse($applicationConfig->isDevelopment());
    }

    public function testPersistCacheDirectory()
    {
        $applicationConfig = new ApplicationConfig(null, null, null, null, 'testDirectory');
        $this->assertSame("testDirectory/", $applicationConfig->getPersistCacheDirectory());

        $applicationConfig = new ApplicationConfig(null, null, null, null, 'testDirectory/');
        $this->assertSame("testDirectory/", $applicationConfig->getPersistCacheDirectory());

        $applicationConfig = new ApplicationConfig(null, null, null, null, '');
        $this->assertSame("./", $applicationConfig->getPersistCacheDirectory());
    }

    public function testBootstrapDirectory()
    {
        $applicationConfig = new ApplicationConfig(null, null,  'testDirectory');
        $this->assertSame("testDirectory/", $applicationConfig->getBootstrapDirectory());

        $applicationConfig = new ApplicationConfig(null, null,  'testDirectory/');
        $this->assertSame("testDirectory/", $applicationConfig->getBootstrapDirectory());

        $applicationConfig = new ApplicationConfig(null, null,  '');
        $this->assertSame("./", $applicationConfig->getBootstrapDirectory());
    }

    public function testConfigDirectory()
    {
        $applicationConfig = new ApplicationConfig(null, 'testDirectory');
        $this->assertSame("testDirectory/", $applicationConfig->getConfigDirectory());

        $applicationConfig = new ApplicationConfig(null, 'testDirectory/');
        $this->assertSame("testDirectory/", $applicationConfig->getConfigDirectory());

        $applicationConfig = new ApplicationConfig(null, '');
        $this->assertSame("./", $applicationConfig->getConfigDirectory());
    }

    public function testCacheDirectory()
    {
        $applicationConfig = new ApplicationConfig(null, null, null,'testDirectory');
        $this->assertSame("testDirectory/", $applicationConfig->getCacheDirectory());

        $applicationConfig = new ApplicationConfig(null, null, null,'testDirectory/');
        $this->assertSame("testDirectory/", $applicationConfig->getCacheDirectory());

        $applicationConfig = new ApplicationConfig(null, null, null,'');
        $this->assertSame("./", $applicationConfig->getCacheDirectory());
    }

    public function testBootstrapQueue()
    {
        $applicationConfig = new ApplicationConfig(
            null,
            null,
            null,
            null,
            null,
            [ServiceManagerBootstrap::class]
        );
        $this->assertSame([ServiceManagerBootstrap::class], $applicationConfig->getBootstrapQueue());

        $applicationConfig = new ApplicationConfig(
            null,
            null,
            null,
            null,
            null,
            ['test' => ServiceManagerBootstrap::class]
        );
        $this->assertSame([ServiceManagerBootstrap::class], $applicationConfig->getBootstrapQueue());
    }

    public function testSerialize()
    {
        $config = [
            'development'                   => false,
            'persistCacheDirectory'         => 'resources/application_test/',
            'cacheDirectory'                => 'data/cache/application_test/',
            'bootstrapDirectory'            => 'bootstrap_test/',
            'configDirectory'               => 'config_test/',
            'bootstrapQueue'                => [ServiceManagerBootstrap::class],
        ];

        $applicationConfig = new ApplicationConfig(
            $config['development'],
            $config['configDirectory'],
            $config['bootstrapDirectory'],
            $config['cacheDirectory'],
            $config['persistCacheDirectory'],
            $config['bootstrapQueue']
        );

        $this->assertSame(\serialize($config), $applicationConfig->serialize());
    }

    public function testUnserialize()
    {
        $config = [
            'development'                   => false,
            'persistCacheDirectory'         => 'resources/application_test/',
            'cacheDirectory'                => 'data/cache/application_test/',
            'bootstrapDirectory'            => 'bootstrap_test/',
            'configDirectory'               => 'config_test/',
            'bootstrapQueue'                => [ServiceManagerBootstrap::class],
        ];

        $applicationConfig = new ApplicationConfig();
        $applicationConfig->unserialize(\serialize($config));

        $this->assertSame($config['development'], $applicationConfig->isDevelopment());
        $this->assertSame($config['persistCacheDirectory'], $applicationConfig->getPersistCacheDirectory());
        $this->assertSame($config['cacheDirectory'], $applicationConfig->getCacheDirectory());
        $this->assertSame($config['bootstrapDirectory'], $applicationConfig->getBootstrapDirectory());
        $this->assertSame($config['bootstrapQueue'], $applicationConfig->getBootstrapQueue());
        $this->assertSame($config['configDirectory'], $applicationConfig->getConfigDirectory());
    }
}
