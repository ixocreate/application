<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Console;

use Ixocreate\Application\Console\ConsoleBootstrapItem;
use Ixocreate\Application\Console\ConsoleConfigurator;
use PHPUnit\Framework\TestCase;

/** @covers \Ixocreate\Application\Console\ConsoleBootstrapItem */
class ConsoleBootstrapItemTest extends TestCase
{
    public function testBootstrapItem()
    {
        $bootstrapItem = new ConsoleBootstrapItem();

        $this->assertInstanceOf(ConsoleConfigurator::class, $bootstrapItem->getConfigurator());
        $this->assertEquals('console', $bootstrapItem->getVariableName());
        $this->assertEquals('console.php', $bootstrapItem->getFileName());
    }
}
