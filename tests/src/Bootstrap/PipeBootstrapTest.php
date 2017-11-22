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
use KiwiSuite\Application\Bootstrap\PipeBootstrap;
use KiwiSuite\Application\Http\Pipe\PipeConfig;
use PHPUnit\Framework\TestCase;

class PipeBootstrapTest extends TestCase
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    public function setUp()
    {
        $this->applicationConfig = new ApplicationConfig([
            'bootstrapDirectory' => __DIR__ . '/../../bootstrap',
        ]);
    }

    public function testBootstrap()
    {
        $pipeBootstrap = new PipeBootstrap();
        $bootstrapItemResult = $pipeBootstrap->bootstrap($this->applicationConfig);

        $this->assertArrayHasKey(PipeConfig::class, $bootstrapItemResult->getHelpers());
        $this->assertInstanceOf(PipeConfig::class, $bootstrapItemResult->getHelpers()[PipeConfig::class]);

        //TODO check include
    }
}
