<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Misc\Application\BootstrapItemDummy;
use Ixocreate\Misc\Application\PackageDummy;
use PHPUnit\Framework\TestCase;

class ApplicationConfigTest extends TestCase
{
    public function testDefaults()
    {
        $applicationConfig = new ApplicationConfig(new ApplicationConfigurator('bootstrap'));

        $this->assertSame(true, $applicationConfig->isDevelopment());
        $this->assertSame('resources/generated/application/', $applicationConfig->getPersistCacheDirectory());
        $this->assertSame('bootstrap/', $applicationConfig->getBootstrapDirectory());
        $this->assertSame('local/', $applicationConfig->getBootstrapEnvDirectory());
        $this->assertSame('data/cache/application/', $applicationConfig->getCacheDirectory());
    }

    public function testDevelopment()
    {
        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->setDevelopment(true);
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertTrue($applicationConfig->isDevelopment());

        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->setDevelopment(false);
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertFalse($applicationConfig->isDevelopment());
    }

    public function testPersistCacheDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->setPersistCacheDirectory('testDirectory');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('testDirectory/', $applicationConfig->getPersistCacheDirectory());

        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->setPersistCacheDirectory('testDirectory/');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('testDirectory/', $applicationConfig->getPersistCacheDirectory());

        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->setPersistCacheDirectory('');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('./', $applicationConfig->getPersistCacheDirectory());
    }

    public function testBootstrapDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator('testDirectory');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('testDirectory/', $applicationConfig->getBootstrapDirectory());

        $applicationConfigurator = new ApplicationConfigurator('testDirectory/');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('testDirectory/', $applicationConfig->getBootstrapDirectory());

        $applicationConfigurator = new ApplicationConfigurator('');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('./', $applicationConfig->getBootstrapDirectory());
    }

    public function testBootstrapEnvDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame($applicationConfigurator->getBootstrapEnvDirectory(), $applicationConfig->getBootstrapEnvDirectory());

        $applicationConfigurator = new ApplicationConfigurator('bootstrap/');
        $applicationConfigurator->setBootstrapEnvDirectory('someDirectory');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('someDirectory/', $applicationConfig->getBootstrapEnvDirectory());
    }

    public function testCacheDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->setCacheDirectory('testDirectory');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('testDirectory/', $applicationConfig->getCacheDirectory());

        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->setCacheDirectory('testDirectory/');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('testDirectory/', $applicationConfig->getCacheDirectory());

        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->setCacheDirectory('');
        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertSame('./', $applicationConfig->getCacheDirectory());
    }

    public function testBootstrapItems()
    {
        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->addBootstrapItem(BootstrapItemDummy::class);

        $applicationConfig = new ApplicationConfig($applicationConfigurator);
        $this->assertInstanceOf(BootstrapItemDummy::class, $applicationConfig->getBootstrapItems()[0]);
    }

    public function testPackages()
    {
        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
        $applicationConfigurator->addPackage(PackageDummy::class);
        $applicationConfig = new ApplicationConfig($applicationConfigurator);

        $this->assertInstanceOf(PackageDummy::class, $applicationConfig->getPackages()[0]);
    }

    public function testSerialize()
    {
        $config = [
            'development' => false,
            'persistCacheDirectory' => 'resources/application_test/',
            'cacheDirectory' => 'data/cache/application_test/',
            'bootstrapDirectory' => 'bootstrap_test/',
            'bootstrapEnvDirectory' => 'local/',
            'bootstrapItems' => [BootstrapItemDummy::class],
            'packages' => [PackageDummy::class],
            'errorDisplay' => false,
            'errorDisplayIps' => ['1.2.3.4'],
            'errorTemplate' => 'test_template',
            'bootPackages' => [PackageDummy::class],
        ];
        $applicationConfigurator = new ApplicationConfigurator($config['bootstrapDirectory']);
        $applicationConfigurator->setDevelopment($config['development']);
        $applicationConfigurator->setPersistCacheDirectory($config['persistCacheDirectory']);
        $applicationConfigurator->setCacheDirectory($config['cacheDirectory']);
        $applicationConfigurator->setBootstrapEnvDirectory($config['bootstrapEnvDirectory']);
        $applicationConfigurator->addBootstrapItem($config['bootstrapItems'][0]);
        $applicationConfigurator->addPackage($config['packages'][0]);
        $applicationConfigurator->setErrorDisplay($config['errorDisplay']);
        $applicationConfigurator->setErrorDisplayIps($config['errorDisplayIps']);
        $applicationConfigurator->setErrorTemplate($config['errorTemplate']);

        $applicationConfig = new ApplicationConfig($applicationConfigurator);

        $this->assertSame(\serialize($config), $applicationConfig->serialize());
    }

    public function testUnserialize()
    {
        $config = [
            'development' => false,
            'persistCacheDirectory' => 'resources/application_test/',
            'cacheDirectory' => 'data/cache/application_test/',
            'bootstrapDirectory' => 'bootstrap_test/',
            'bootstrapEnvDirectory' => 'local/',
            'bootstrapItems' => [BootstrapItemDummy::class],
            'packages' => [PackageDummy::class],
            'errorDisplay' => false,
            'errorDisplayIps' => ['1.2.3.4'],
            'errorTemplate' => 'test_template',
        ];

        $applicationConfig = new ApplicationConfig(new ApplicationConfigurator($config['bootstrapDirectory']));
        $applicationConfig->unserialize(\serialize($config));

        $this->assertSame($config['development'], $applicationConfig->isDevelopment());
        $this->assertSame($config['persistCacheDirectory'], $applicationConfig->getPersistCacheDirectory());
        $this->assertSame($config['cacheDirectory'], $applicationConfig->getCacheDirectory());
        $this->assertSame($config['bootstrapDirectory'], $applicationConfig->getBootstrapDirectory());
        $this->assertSame($config['errorDisplay'], $applicationConfig->isErrorDisplay());
        $this->assertSame($config['errorDisplayIps'], $applicationConfig->errorDisplayIps());
        $this->assertSame($config['errorTemplate'], $applicationConfig->errorTemplate());

        $this->assertInstanceOf(BootstrapItemDummy::class, $applicationConfig->getBootstrapItems()[0]);
        $this->assertInstanceOf(PackageDummy::class, $applicationConfig->getPackages()[0]);
    }
}
