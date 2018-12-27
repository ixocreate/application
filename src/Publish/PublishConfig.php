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

namespace Ixocreate\Application\Publish;

use Ixocreate\Contract\Application\SerializableServiceInterface;

final class PublishConfig implements SerializableServiceInterface
{
    private $sources = [];

    public function __construct(PublishConfigurator $publishConfigurator)
    {
        $this->sources = $publishConfigurator->getSources();
    }

    /**
     * @param string $name
     * @return array
     */
    public function getSources(string $name): array
    {
        if (\array_key_exists($name, $this->sources)) {
            return $this->sources[$name];
        }

        return [];
    }

    /**
     * @return string|void
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }
}
