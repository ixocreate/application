<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Publish;

use Ixocreate\Application\Service\SerializableServiceInterface;

final class PublishConfig implements SerializableServiceInterface
{
    /**
     * @var array
     */
    private $definitions = [];

    public function __construct(PublishConfigurator $publishConfigurator)
    {
        $sources = $publishConfigurator->getSources();

        foreach ($publishConfigurator->getDefinitions() as $name => $spec) {
            $this->definitions[$name] = $spec;
            $this->definitions[$name]['sources'] = $sources[$name];
        }
    }

    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getDefinition(string $name): ?array
    {
        if (\array_key_exists($name, $this->definitions)) {
            return $this->definitions[$name];
        }

        return null;
    }

    /**
     * @return string|void
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
