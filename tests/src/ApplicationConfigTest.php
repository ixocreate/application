<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateTest\Application;

use Ixocreate\Application\ApplicationConfig;
use IxocreateMisc\Application\BootstrapDummy;
use IxocreateMisc\Application\ConfiguratorItemDummy;
use IxocreateMisc\Application\PackageDummy;
use PHPUnit\Framework\TestCase;

class ApplicationConfigTest extends TestCase
{
    public function testDefaults()
    {
        $applicationConfig = new ApplicationConfig();

        $this->assertSame(true, $applicationConfig->isDevelopment());
        $this->assertSame("resources/application/", $applicationConfig->getPersistCacheDirectory());
        $this->assertSame([], $applicationConfig->getBootstrapQueue());
        $this->assertSame([], $applicationConfig->getModules());
        $this->assertSame([], $applicationConfig->getConfiguratorItems());
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
        $applicationConfig = new ApplicationConfig(null, null, 'testDirectory');
        $this->assertSame("testDirectory/", $applicationConfig->getBootstrapDirectory());

        $applicationConfig = new ApplicationConfig(null, null, 'testDirectory/');
        $this->assertSame("testDirectory/", $applicationConfig->getBootstrapDirectory());

        $applicationConfig = new ApplicationConfig(null, null, '');
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
        $applicationConfig = new ApplicationConfig(null, null, null, 'testDirectory');
        $this->assertSame("testDirectory/", $applicationConfig->getCacheDirectory());

        $applicationConfig = new ApplicationConfig(null, null, null, 'testDirectory/');
        $this->assertSame("testDirectory/", $applicationConfig->getCacheDirectory());

        $applicationConfig = new ApplicationConfig(null, null, null, '');
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
            [BootstrapDummy::class]
        );
        $this->assertInstanceOf(BootstrapDummy::class, $applicationConfig->getBootstrapQueue()[0]);

        $applicationConfig = new ApplicationConfig(
            null,
            null,
            null,
            null,
            null,
            ['test' => BootstrapDummy::class]
        );
        $this->assertInstanceOf(BootstrapDummy::class, $applicationConfig->getBootstrapQueue()[0]);
    }

    public function testModules()
    {
        $applicationConfig = new ApplicationConfig(
            null,
            null,
            null,
            null,
            null,
            null,
            [],
            [PackageDummy::class]
        );
        $this->assertInstanceOf(PackageDummy::class, $applicationConfig->getModules()[0]);

        $applicationConfig = new ApplicationConfig(
            null,
            null,
            null,
            null,
            null,
            null,
            [],
            [PackageDummy::class]
        );
        $this->assertInstanceOf(PackageDummy::class, $applicationConfig->getModules()[0]);
    }

    public function testSerialize()
    {
        $config = [
            'development'                   => false,
            'persistCacheDirectory'         => 'resources/application_test/',
            'cacheDirectory'                => 'data/cache/application_test/',
            'bootstrapDirectory'            => 'bootstrap_test/',
            'configDirectory'               => 'config_test/',
            'bootstrapQueue'                => [BootstrapDummy::class],
            'configurators'                 => [ConfiguratorItemDummy::class],
            'modules'                       => [PackageDummy::class],
        ];

        $applicationConfig = new ApplicationConfig(
            $config['development'],
            $config['configDirectory'],
            $config['bootstrapDirectory'],
            $config['cacheDirectory'],
            $config['persistCacheDirectory'],
            $config['bootstrapQueue'],
            $config['configurators'],
            $config['modules']
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
            'bootstrapQueue'                => [BootstrapDummy::class],
            'configurators'                 => [ConfiguratorItemDummy::class],
            'modules'                       => [PackageDummy::class],
        ];

        $applicationConfig = new ApplicationConfig();
        $applicationConfig->unserialize(\serialize($config));

        $this->assertSame($config['development'], $applicationConfig->isDevelopment());
        $this->assertSame($config['persistCacheDirectory'], $applicationConfig->getPersistCacheDirectory());
        $this->assertSame($config['cacheDirectory'], $applicationConfig->getCacheDirectory());
        $this->assertSame($config['bootstrapDirectory'], $applicationConfig->getBootstrapDirectory());
        $this->assertSame($config['configDirectory'], $applicationConfig->getConfigDirectory());

        $this->assertInstanceOf(BootstrapDummy::class, $applicationConfig->getBootstrapQueue()[0]);
        $this->assertInstanceOf(PackageDummy::class, $applicationConfig->getModules()[0]);
        $this->assertInstanceOf(ConfiguratorItemDummy::class, $applicationConfig->getConfiguratorItems()[0]);
    }
}
