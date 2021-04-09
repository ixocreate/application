<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Console;

use Ixocreate\Application\Console\CommandMapping;
use Ixocreate\Application\Service\SerializableServiceInterface;
use PHPUnit\Framework\TestCase;

class CommandMappingTest extends TestCase
{
    public function testMapping()
    {
        $mapping = [
            'someCommand' => \DateTime::class,
            'someOtherCommand' => \DateTime::class,
        ];
        $commandMapping = new CommandMapping($mapping);

        $this->assertInstanceOf(SerializableServiceInterface::class, $commandMapping);
        $this->assertEquals($mapping, $commandMapping->getMapping());

        $s = \serialize($commandMapping);
        $this->assertEquals($mapping, \unserialize($s)->getMapping());
    }
}
