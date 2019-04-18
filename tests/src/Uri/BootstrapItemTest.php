<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Uri;

use Ixocreate\Application\Uri\ApplicationUriBootstrapItem;
use Ixocreate\Application\Uri\ApplicationUriConfigurator;
use PHPUnit\Framework\TestCase;

class BootstrapItemTest extends TestCase
{
    /**
     * @covers \Ixocreate\Application\Uri\ApplicationUriBootstrapItem
     */
    public function testBootstrapItem()
    {
        $item = new ApplicationUriBootstrapItem();

        $configurator = $item->getConfigurator();

        $this->assertInstanceOf(ApplicationUriConfigurator::class, $configurator);
        $this->assertEquals('projectUri', $item->getVariableName());
        $this->assertEquals('application-uri.php', $item->getFileName());
    }
}
