<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Service;

use Ixocreate\Application\Exception\ServiceNotFoundException;
use Ixocreate\Application\Service\ServiceRegistry;
use PHPUnit\Framework\TestCase;

class ServiceRegistryTest extends TestCase
{
    public function testServices()
    {
        $class = new class() implements \Serializable {
            /**
             * @return string|void
             */
            public function serialize()
            {
                return \serialize(null);
            }

            /**
             * @param string $serialized
             */
            public function unserialize($serialized)
            {
                \unserialize($this->serialize());
            }
        };

        $serviceRegistry = new ServiceRegistry();
        $serviceRegistry->add("test", $class);
        $this->assertArrayHasKey("test", $serviceRegistry->all());
        $this->assertTrue($serviceRegistry->has("test"));
        $this->assertFalse($serviceRegistry->has(\DateTime::class));
        $this->assertSame($class, $serviceRegistry->get("test"));

        $this->expectException(ServiceNotFoundException::class);
        $serviceRegistry->get(\DateTime::class);
    }
}
