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
use KiwiSuite\Application\Bootstrap\ConfigBootstrap;
use KiwiSuite\Application\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigBootstrapTest extends TestCase
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    public function setUp()
    {
        $this->applicationConfig = new ApplicationConfig([
            'bootstrapDirectory' => __DIR__ . '/../../bootstrap',
            'configDirectory' => __DIR__ . '/../../config',
        ]);
    }

    public function testBootstrap()
    {
        $configBootstrap = new ConfigBootstrap();
        $bootstrapItemResult = $configBootstrap->bootstrap($this->applicationConfig);

        $this->assertArrayHasKey(Config::class, $bootstrapItemResult->getServices());
        $this->assertInstanceOf(Config::class, $bootstrapItemResult->getServices()[Config::class]);

        /** @var Config $config */
        $config = $bootstrapItemResult->getServices()[Config::class];

        $this->assertTrue($config->has("db"));
        $this->assertSame("mynewpass", $config->get("db.pass"));
        $this->assertSame("myuser", $config->get("db.user"));
        $this->assertSame("myhost", $config->get("db.host"));
    }

    public function testMissingDirectory()
    {
        $applicationConfig = new ApplicationConfig([
            'bootstrapDirectory' => __DIR__ . '/../../bootstrap',
            'configDirectory' => 'doesntexist',
        ]);

        $configBootstrap = new ConfigBootstrap();
        $bootstrapItemResult = $configBootstrap->bootstrap($applicationConfig);

        $this->assertArrayHasKey(Config::class, $bootstrapItemResult->getServices());
        $this->assertInstanceOf(Config::class, $bootstrapItemResult->getServices()[Config::class]);

        /** @var Config $config */
        $config = $bootstrapItemResult->getServices()[Config::class];

        $this->assertSame([], $config->all());
    }
}
