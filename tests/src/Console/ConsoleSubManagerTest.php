<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Console;

use Ixocreate\Application\Console\ConsoleSubManager;
use Ixocreate\Application\Console\Factory\CommandMapFactory;
use Ixocreate\ServiceManager\ServiceManagerConfigInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use PHPUnit\Framework\TestCase;

class ConsoleSubManagerTest extends TestCase
{
    public function testGetNames()
    {
        $factories = [
            'someService' => 'someFactory',
            'command1' => CommandMapFactory::class,
            'someOtherService' => 'someFactory',
            'command2' => CommandMapFactory::class,
        ];

        $parentSm = $this->createMock(ServiceManagerInterface::class);
        $config = $this->createMock(ServiceManagerConfigInterface::class);
        $config->method('getFactories')->willReturn($factories);

        $sm = new ConsoleSubManager($parentSm, $config);

        $this->assertEquals(['command1', 'command2'], $sm->getNames());
    }
}
