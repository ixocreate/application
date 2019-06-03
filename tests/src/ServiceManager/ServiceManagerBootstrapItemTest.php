<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\ServiceManager;

use Ixocreate\Application\ServiceManager\ServiceManagerBootstrapItem;
use Ixocreate\Application\ServiceManager\ServiceManagerConfigurator;
use PHPUnit\Framework\TestCase;

class ServiceManagerBootstrapItemTest extends TestCase
{
    /**
     * @var ServiceManagerBootstrapItem
     */
    private $bootstrapItem;

    public function setUp()
    {
        $this->bootstrapItem = new ServiceManagerBootstrapItem();
    }

    public function testConfigurator(): void
    {
        $this->assertInstanceOf(ServiceManagerConfigurator::class, $this->bootstrapItem->getConfigurator());
    }

    public function testVariableName(): void
    {
        $this->assertEquals('serviceManager', $this->bootstrapItem->getVariableName());
    }

    public function testFileName(): void
    {
        $this->assertEquals('servicemanager.php', $this->bootstrapItem->getFileName());
    }
}
