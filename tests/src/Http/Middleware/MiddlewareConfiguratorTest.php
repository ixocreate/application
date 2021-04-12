<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Http\Middleware;

use Ixocreate\Application\Http\Middleware\MiddlewareConfigurator;
use Ixocreate\Application\Http\Middleware\MiddlewareSubManager;
use Ixocreate\Application\Service\ServiceRegistry;
use Ixocreate\Application\ServiceManager\SubManagerConfig;
use PHPUnit\Framework\TestCase;

/** @covers \Ixocreate\Application\Http\Middleware\MiddlewareConfigurator */
class MiddlewareConfiguratorTest extends TestCase
{
    public function testMiddlewareConfigurator()
    {
        $configurator = new MiddlewareConfigurator();

        $configurator->addAction('SomeDummyAction');
        $configurator->addMiddleware('SomeDummyMiddleware');

        $registry = new ServiceRegistry();
        $configurator->registerService($registry);

        $this->assertTrue($registry->has(MiddlewareSubManager::class . '::Config'));
        /** @var SubManagerConfig $subManagerConfig */
        $subManagerConfig = $registry->get(MiddlewareSubManager::class . '::Config');

        $this->assertArrayHasKey('SomeDummyAction', $subManagerConfig->getFactories());
        $this->assertArrayHasKey('SomeDummyMiddleware', $subManagerConfig->getFactories());
    }
}
