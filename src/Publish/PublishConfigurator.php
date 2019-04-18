<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Publish;

use Ixocreate\Application\ConfiguratorInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;

final class PublishConfigurator implements ConfiguratorInterface
{
    /**
     * @var array
     */
    private $publish = [];

    /**
     * @param string $name
     * @param string $source
     */
    public function add(string $name, string $source): void
    {
        if (!\array_key_exists($name, $this->publish)) {
            $this->publish[$name] = [];
        }
        $source = \realpath($source);
        $source = \str_replace(\getcwd() . '/', "", $source);

        $this->publish[$name][] = $source;
    }

    /**
     * @return array
     */
    public function getSources(): array
    {
        return $this->publish;
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add(PublishConfig::class, new PublishConfig($this));
    }
}
