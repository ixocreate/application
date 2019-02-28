<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateTest\Application;

use Ixocreate\Application\ApplicationConfigurator;
use IxocreateMisc\Application\BootstrapDummy;
use IxocreateMisc\Application\PackageDummy;
use PHPUnit\Framework\TestCase;

class ApplicationConfiguratorTest extends TestCase
{
    public function testDefaults()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");

        $this->assertSame("bootstrap/", $applicationConfigurator->getBootstrapDirectory());
        $this->assertSame("local/", $applicationConfigurator->getBootstrapEnvDirectory());
        $this->assertSame(true, $applicationConfigurator->isDevelopment());
        $this->assertSame("data/cache/application/", $applicationConfigurator->getCacheDirectory());
        $this->assertSame("resource/application/", $applicationConfigurator->getPersistCacheDirectory());
        $this->assertSame("config/", $applicationConfigurator->getConfigDirectory());
        $this->assertSame("local/", $applicationConfigurator->getConfigEnvDirectory());
        $this->assertSame([], $applicationConfigurator->getPackages());
        $this->assertSame([], $applicationConfigurator->getBootstrapItems());
    }

    public function testBootstrapDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $this->assertSame("bootstrap/", $applicationConfigurator->getBootstrapDirectory());
    }

    public function testBootstrapEnvDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setBootstrapEnvDirectory('dev');
        $this->assertSame("dev/", $applicationConfigurator->getBootstrapEnvDirectory());
    }

    public function testDevelopment()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setDevelopment(false);
        $this->assertFalse($applicationConfigurator->isDevelopment());
    }

    public function testPersistCacheDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setPersistCacheDirectory("directory");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("directory/", $applicationConfig->getPersistCacheDirectory());
    }

    public function testCacheDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setCacheDirectory("directory");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("directory/", $applicationConfig->getCacheDirectory());
    }

    public function testConfigDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setConfigDirectory("directory");
        $this->assertSame("directory/", $applicationConfigurator->getConfigDirectory());
    }

    public function testConfigEnvDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setConfigEnvDirectory("directory");
        $this->assertSame("directory/", $applicationConfigurator->getConfigEnvDirectory());
    }

    public function testAddPackage()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->addPackage(PackageDummy::class);
        $this->assertCount(1, $applicationConfigurator->getPackages());
        $this->assertInstanceOf(PackageDummy::class, $applicationConfigurator->getPackages()[0]);
    }

    public function testAddBootstrapItem()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->addBootstrapItem(BootstrapDummy::class);
        $this->assertCount(1, $applicationConfigurator->getBootstrapItems());
        $this->assertInstanceOf(BootstrapDummy::class, $applicationConfigurator->getBootstrapItems()[0]);
    }
}
