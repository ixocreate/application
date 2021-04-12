<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Console\Factory;

use Ixocreate\Application\Console\CommandMapping;
use Ixocreate\Application\Console\ConsoleSubManager;
use Ixocreate\Application\Console\Factory\CommandMapFactory;
use Ixocreate\ServiceManager\Exception\ServiceNotCreatedException;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use PHPUnit\Framework\TestCase;

/** @covers \Ixocreate\Application\Console\Factory\CommandMapFactory */
class CommandMapFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new CommandMapFactory();

        $mapping = $this->createMock(CommandMapping::class);
        $mapping->method('getMapping')->willReturn([
            'mapping1' => 'mappingClass1',
            'mapping2' => 'mappingClass2',
        ]);

        $dummyClass = new \DateTime();
        $subManager = $this->createMock(ConsoleSubManager::class);
        $subManager->method('get')->willReturn($dummyClass);

        $container = $this->createMock(ServiceManagerInterface::class);
        $container->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                [CommandMapping::class, $mapping],
                [ConsoleSubManager::class, $subManager],
            ]);

        $command = $factory($container, 'mapping1');

        $this->assertEquals($dummyClass, $command);
    }

    public function testException()
    {
        $factory = new CommandMapFactory();

        $this->expectException(ServiceNotCreatedException::class);

        $mapping = $this->createMock(CommandMapping::class);
        $mapping->method('getMapping')->willReturn([
            'mapping1' => 'mappingClass1',
            'mapping2' => 'mappingClass2',
        ]);

        $container = $this->createMock(ServiceManagerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(CommandMapping::class)
            ->willReturn($mapping);

        $factory($container, 'someName');
    }
}
