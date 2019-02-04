<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateTest\Application;

use Ixocreate\Application\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private $configArray = [
        'series' => [
            'season1' => [
                'eps1.0_hellofriend.mov',
                'eps1.1_ones-and-zer0es.mpeg',
            ],
            'season2' => [
                'eps2.0_unm4sk-pt1.tc',
                'eps2.0_unm4sk-pt2.tc',
            ],
        ],
    ];

    public function testAll()
    {
        $config = new Config($this->configArray);

        $this->assertSame($this->configArray, $config->all());
    }

    public function testHas()
    {
        $config = new Config($this->configArray);

        $this->assertTrue($config->has("series"));
        $this->assertTrue($config->has("series.season1"));
        $this->assertTrue($config->has("series.season2"));
        $this->assertTrue($config->has("series.season2.0"));

        $this->assertFalse($config->has("doesntExist"));
        $this->assertFalse($config->has("doesntExist.test"));
        $this->assertFalse($config->has("series.season3"));
        $this->assertFalse($config->has("series.season2.4"));
    }

    public function testGet()
    {
        $config = new Config($this->configArray);

        $this->assertNull($config->get("doesntExist"));
        $this->assertSame("test", $config->get("doesntExist", "test"));

        $this->assertSame($this->configArray['series'], $config->get("series"));
        $this->assertSame($this->configArray['series']['season1'], $config->get("series.season1"));
        $this->assertSame($this->configArray['series']['season1'][0], $config->get("series.season1.0"));
    }
}
