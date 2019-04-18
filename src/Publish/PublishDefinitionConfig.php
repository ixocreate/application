<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Publish;

use Ixocreate\Application\SerializableServiceInterface;

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
     * @return string
     */
    public function serialize()
    {
        return \serialize($this->definitions);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->definitions = \unserialize($serialized);
    }
}
