<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Publish;

use Ixocreate\Application\SerializableServiceInterface;

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
        return \serialize($this->sources);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->sources = \unserialize($serialized);
    }
}
