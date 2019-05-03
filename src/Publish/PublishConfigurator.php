<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Publish;

use Ixocreate\Application\Configurator\ConfiguratorInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;

final class PublishConfigurator implements ConfiguratorInterface
{
    /**
     * @var array
     */
    private $sources = [];

    /**
     * @var array
     */
    private $publish = [];

    /**
     * @param string $name
     * @param string $source
     */
    public function addSource(string $name, string $source): void
    {
        if (!\array_key_exists($name, $this->sources)) {
            $this->sources[$name] = [];
        }
        $source = \realpath($source);
        $source = \str_replace(\getcwd() . '/', '', $source);

        $this->sources[$name][] = $source;
    }

    /**
     * @param string $name
     */
    public function removeSource(string $name): void
    {
        if (\array_key_exists($name, $this->sources)) {
            unset($this->sources[$name]);
        }
    }

    /**
     * @return array
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * @param string $name
     * @param string $targetDirectory
     */
    public function addDefinition(string $name, string $targetDirectory): void
    {
        $this->publish[$name] = [
            'targetDirectory' => \rtrim($targetDirectory, '/') . '/',
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
        $serviceRegistry->add(PublishConfig::class, new PublishConfig($this));
    }
}
