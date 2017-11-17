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

use KiwiSuite\Application\ApplicationConfigurator;
use KiwiSuite\Application\Bootstrap\ConfigBootstrap;
use KiwiSuite\Application\Bootstrap\ServiceManagerBootstrap;
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
    }

    public function testBootstrapDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("bootstrap/", $applicationConfig->getBootstrapDirectory());

        $applicationConfigurator = new ApplicationConfigurator("bootstrap/");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("bootstrap/", $applicationConfig->getBootstrapDirectory());

        $applicationConfigurator = new ApplicationConfigurator("");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("./", $applicationConfig->getBootstrapDirectory());

        $applicationConfigurator = new ApplicationConfigurator("/");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("/", $applicationConfig->getBootstrapDirectory());
    }

    public function testDevelopment()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setDevelopment(true);
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertTrue($applicationConfig->isDevelopment());

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

        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setPersistCacheDirectory("directory/");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("directory/", $applicationConfig->getPersistCacheDirectory());

        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setPersistCacheDirectory("");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("./", $applicationConfig->getPersistCacheDirectory());

        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setPersistCacheDirectory("/");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("/", $applicationConfig->getPersistCacheDirectory());
    }

    public function testCacheDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setCacheDirectory("directory");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("directory/", $applicationConfig->getCacheDirectory());

        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setCacheDirectory("directory/");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("directory/", $applicationConfig->getCacheDirectory());

        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setCacheDirectory("");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("./", $applicationConfig->getCacheDirectory());

        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setCacheDirectory("/");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("/", $applicationConfig->getCacheDirectory());
    }

    public function testConfigDirectory()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setConfigDirectory("directory");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("directory/", $applicationConfig->getConfigDirectory());

        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setConfigDirectory("directory/");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("directory/", $applicationConfig->getConfigDirectory());

        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setConfigDirectory("");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("./", $applicationConfig->getConfigDirectory());

        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->setConfigDirectory("/");
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame("/", $applicationConfig->getConfigDirectory());
    }

    public function testAddBootstrapItem()
    {
        $applicationConfigurator = new ApplicationConfigurator("bootstrap");
        $applicationConfigurator->addBootstrapItem(ServiceManagerBootstrap::class, 50);
        $applicationConfigurator->addBootstrapItem(ConfigBootstrap::class, 80);
        $applicationConfig = $applicationConfigurator->getApplicationConfig();
        $this->assertSame([ConfigBootstrap::class, ServiceManagerBootstrap::class], $applicationConfig->getBootstrapQueue());

        //TODO check priority
    }
}
