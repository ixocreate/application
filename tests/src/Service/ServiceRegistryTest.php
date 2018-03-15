<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuiteTest\Application\Service;

use KiwiSuite\Application\Exception\ArgumentNotFoundException;
use KiwiSuite\Application\Service\ServiceRegistry;
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

        $this->expectException(ArgumentNotFoundException::class);
        $serviceRegistry->get(\DateTime::class);
    }
}
