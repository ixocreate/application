<?php
declare(strict_types=1);

namespace KiwiSuite\Application\Publish;

use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;

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
        $source = realpath($source);
        $source = str_replace(getcwd() . '/', "", $source);

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
