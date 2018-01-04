<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteTest\Application;

use KiwiSuite\Application\ApplicationConfigurator;
use KiwiSuiteMisc\Application\BootstrapTest;
use KiwiSuiteMisc\Application\BundleTest;
use KiwiSuiteMisc\Application\ModuleTest;
use PHPUnit\Framework\TestCase;

class ApplicationConfiguratorTest extends TestCase
{
    public function testDefaults()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();

        $this->assertSame("bootstrap/", $applicationConfig->getBootstrapDirectory());
        $this->assertSame(true, $applicationConfig->isDevelopment());
        $this->assertSame([], $applicationConfig->getBootstrapQueue());
        $this->assertSame("data/cache/application/", $applicationConfig->getCacheDirectory());
        $this->assertSame("resource/application/", $applicationConfig->getPersistCacheDirectory());
        $this->assertSame("config/", $applicationConfig->getConfigDirectory());
        $this->assertSame([], $applicationConfig->getModules());
    }

    public function testBootstrapDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("bootstrap/", $applicationConfig->getBootstrapDirectory());
    }

    public function testDevelopment()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setDevelopment(false);
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertFalse($applicationConfig->isDevelopment());
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
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("directory/", $applicationConfig->getConfigDirectory());
    }

    public function testAddModules()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->addModule(ModuleTest::class);
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertInstanceOf(ModuleTest::class, $applicationConfig->getModules()[0]);
    }

    public function testAddBundles()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->addBundle(BundleTest::class);
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertInstanceOf(BundleTest::class, $applicationConfig->getBundles()[0]);
    }

    public function testAddBootstrapItem()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->addBootstrapItem(BootstrapTest::class, 50);
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertInstanceOf(BootstrapTest::class, $applicationConfig->getBootstrapQueue()[0]);

        //TODO check priority
    }
}
