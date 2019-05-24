<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application;

use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Misc\Application\BootstrapDummy;
use Ixocreate\Misc\Application\PackageDummy;
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
        $this->assertSame("resources/generated/application/", $applicationConfigurator->getPersistCacheDirectory());
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
        $this->assertSame(PackageDummy::class, $applicationConfigurator->getPackages()[0]);
    }

    public function testAddBootstrapItem()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->addBootstrapItem(BootstrapDummy::class);
        $this->assertCount(1, $applicationConfigurator->getBootstrapItems());
        $this->assertSame(BootstrapDummy::class, $applicationConfigurator->getBootstrapItems()[0]);
    }
}
