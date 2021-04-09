<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Http\Middleware;

use Ixocreate\Application\Http\Middleware\MiddlewareBootstrapItem;
use Ixocreate\Application\Http\Middleware\MiddlewareConfigurator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Application\Http\Middleware\MiddlewareBootstrapItem
 */
class MiddlewareBootstrapItemTest extends TestCase
{
    public function testMiddlewareBootstrapItem()
    {
        $bootstrapItem = new MiddlewareBootstrapItem();

        $this->assertInstanceOf(MiddlewareConfigurator::class, $bootstrapItem->getConfigurator());
        $this->assertEquals('middleware', $bootstrapItem->getVariableName());
        $this->assertEquals('middleware.php', $bootstrapItem->getFileName());
    }
}
