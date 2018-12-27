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

use Ixocreate\Contract\Application\ConfiguratorInterface;
use Ixocreate\Contract\Application\ServiceRegistryInterface;

final class PublishConfigurator implements ConfiguratorInterface
{
    /**
     * @var array
     */
    private $publish = [];

    /**
     * @param string $name
     * @param string $target
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
