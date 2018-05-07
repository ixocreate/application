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

namespace KiwiSuite\Application\Publish;

use KiwiSuite\Contract\Application\SerializableServiceInterface;

final class PublishDefinitionConfig implements SerializableServiceInterface
{
    private $definitions = [];

    public function __construct(PublishDefinitionConfigurator $publishConfigurator)
    {
        $this->definitions = $publishConfigurator->getDefinitions();
    }

    public function getDefinitions(PublishConfig $publishConfig): array
    {
        $definitions = [];

        foreach ($this->definitions as $name => $spec) {
            $definitions[$name] = $spec;
            $definitions[$name]['sources'] = $publishConfig->getSources($name);
        }

        return $definitions;
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
