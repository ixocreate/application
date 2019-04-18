<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Publish;

use Ixocreate\Application\ConfiguratorInterface;
use Ixocreate\Application\ServiceRegistryInterface;

final class PublishDefinitionConfigurator implements ConfiguratorInterface
{
    /**
     * @var array
     */
    private $publish = [];

    /**
     * @param string $name
     * @param string $storage
     */
    public function add(string $name, string $storage): void
    {
        $this->publish[$name] = [
            'storage' => $storage,
        ];
    }

    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->publish;
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add(PublishDefinitionConfig::class, new PublishDefinitionConfig($this));
    }
}
