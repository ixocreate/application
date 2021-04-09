<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Console\Factory;

use Ixocreate\Application\Application;
use Ixocreate\Application\Console\ConsoleRunner;
use Ixocreate\Application\Console\ConsoleSubManager;
use Ixocreate\Application\Console\Factory\ConsoleRunnerFactory;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;

/** @covers \Ixocreate\Application\Console\Factory\ConsoleRunnerFactory */
class ConsoleRunnerFactoryTest extends TestCase
{
    public function testInvoke()
    {
        $commandMock = $this->createMock(Command::class);
        $commandMock->method('isEnabled')->willReturn(true);
        $commandMock->method('getName')->willReturn('someRandomCommand');
        $commandMock->method('getAliases')->willReturn([]);

        $subManager = $this->createMock(ConsoleSubManager::class);
        $subManager->expects($this->once())
            ->method('has')
            ->willReturnCallback(function ($arg) {
                return $arg === 'someRandomCommand';
            });
        $subManager->expects($this->once())
            ->method('get')
            ->willReturnMap([
                ['someRandomCommand', $commandMock],
            ]);

        $container = $this->createMock(ServiceManagerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(ConsoleSubManager::class)
            ->willReturn($subManager);

        $factory = new ConsoleRunnerFactory();

        /** @var ConsoleRunner $application */
        $application = $factory($container, 'someName');
        $this->assertInstanceOf(ConsoleRunner::class, $application);
        $this->assertEquals('IXOCREATE', $application->getName());
        $this->assertEquals(Application::VERSION, $application->getVersion());

        // test commandLoader, must be done indirect via has()
        $this->assertTrue($application->has('someRandomCommand'));
    }
}
