<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Console;

use Ixocreate\Application\Console\CommandMapping;
use Ixocreate\Application\Console\ConsoleConfigurator;
use Ixocreate\Application\Console\ConsoleSubManager;
use Ixocreate\Application\Exception\InvalidArgumentException;
use Ixocreate\Application\Service\ServiceRegistry;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Misc\Application\CommandDummy;
use PHPUnit\Framework\TestCase;

/** @covers \Ixocreate\Application\Console\ConsoleConfigurator */
class ConsoleConfiguratorTest extends TestCase
{
    public function testConsoleConfigurator()
    {
        $configurator = new ConsoleConfigurator();

        $command = new CommandDummy();

        $configurator->addDirectory('some/invalid/directory');
        $configurator->addCommand(\get_class($command));

        $registry = new ServiceRegistry();
        $configurator->registerService($registry);

        $this->assertTrue($registry->has(CommandMapping::class));
        $this->assertTrue($registry->has(ConsoleSubManager::class . '::Config'));
    }

    public function testInvalidCommand()
    {
        $this->expectException(InvalidArgumentException::class);

        $configurator = new ConsoleConfigurator();

        $configurator->addCommand('some');

        $registry = $this->createMock(ServiceRegistryInterface::class);
        $configurator->registerService($registry);
    }
}
