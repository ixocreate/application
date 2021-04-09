<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Console;

use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Application\Console\ConsoleApplication;
use PHPUnit\Framework\TestCase;

class ConsoleApplicationTest extends TestCase
{
    public function testConfigure()
    {
        $application = new ConsoleApplication('/bootstrap');

        $_SERVER['argv'] = ['-d'];
        $configurator = $this->createMock(ApplicationConfigurator::class);
        $configurator->expects($this->once())
            ->method('setDevelopment')
            ->willReturnCallback(function ($param) {
                $this->assertTrue($param);
            });
        $application->configure($configurator);

        $_SERVER['argv'] = ['--development'];
        $configurator = $this->createMock(ApplicationConfigurator::class);
        $configurator->expects($this->once())
            ->method('setDevelopment')
            ->willReturnCallback(function ($param) {
                $this->assertTrue($param);
            });
        $application->configure($configurator);
    }

    public function testEmptyConfigure()
    {
        $application = new ConsoleApplication('/bootstrap');

        unset($_SERVER['argv']);
        $configurator = $this->createMock(ApplicationConfigurator::class);
        $configurator->expects($this->never())
            ->method('setDevelopment');
        $application->configure($configurator);

        $_SERVER['argv'] = 'string';
        $configurator = $this->createMock(ApplicationConfigurator::class);
        $configurator->expects($this->never())
            ->method('setDevelopment');
        $application->configure($configurator);

        $_SERVER['argv'] = ['string'];
        $configurator = $this->createMock(ApplicationConfigurator::class);
        $configurator->expects($this->never())
            ->method('setDevelopment');
        $application->configure($configurator);
    }
}
