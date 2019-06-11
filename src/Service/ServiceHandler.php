<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Service;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Bootstrap\BootstrapItemInclude;
use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Configurator\ConfiguratorRegistry;
use Ixocreate\Application\Package\ConfigureAwareInterface;
use Ixocreate\Application\Package\PackageInterface;
use Ixocreate\Application\Package\ProvideServicesInterface;

final class ServiceHandler
{
    /**
     * @param ApplicationConfig $applicationConfig
     * @return ServiceRegistry
     */
    public function loadFromCache(ApplicationConfig $applicationConfig): ServiceRegistry
    {
        if ($applicationConfig->isDevelopment()) {
            return $this->load($applicationConfig);
        }

        if (!\file_exists($this->getCacheFileName($applicationConfig))) {
            return $this->load($applicationConfig);
        }

        $serviceRegistry = @\unserialize(
            \file_get_contents($this->getCacheFileName($applicationConfig))
        );

        if (!($serviceRegistry instanceof ServiceRegistry)) {
            return $this->load($applicationConfig);
        }

        return $serviceRegistry;
    }

    public function load(ApplicationConfig $applicationConfig): ServiceRegistry
    {
        $configuratorRegistry = new ConfiguratorRegistry();

        foreach ($applicationConfig->getBootstrapItems() as $bootstrapItem) {
            $this->handleBootstrapItem($applicationConfig, $bootstrapItem, $configuratorRegistry);
        }

        foreach ($applicationConfig->getPackages() as $package) {
            if ($package instanceof ConfigureAwareInterface) {
                $package->configure($configuratorRegistry);
            }
        }

        $serviceRegistry = new ServiceRegistry();

        foreach ($applicationConfig->getBootstrapItems() as $bootstrapItem) {
            $configuratorRegistry->get(\get_class($bootstrapItem))->registerService($serviceRegistry);
        }

        foreach ($applicationConfig->getPackages() as $package) {
            if ($package instanceof ProvideServicesInterface) {
                $package->provideServices($serviceRegistry);
            }
        }

        if (!$applicationConfig->isDevelopment()) {
            $this->save($applicationConfig, $serviceRegistry);
        }

        return $serviceRegistry;
    }

    public function save(ApplicationConfig $applicationConfig, $serviceRegistry = null): void
    {
        if ($serviceRegistry === null) {
            $serviceRegistry = $this->load($applicationConfig);
        }

        \file_put_contents(
            $this->getCacheFileName($applicationConfig),
            \serialize($serviceRegistry),
            LOCK_EX
        );
    }

    /**
     * @param ApplicationConfig $applicationConfig
     * @return string
     */
    private function getCacheFileName(ApplicationConfig $applicationConfig): string
    {
        $directory = $applicationConfig->getPersistCacheDirectory();
        if (!\file_exists($directory)) {
            \mkdir($directory, 0777, true);
        }

        return $directory . "services.cache";
    }

    private function handleBootstrapItem(
        ApplicationConfig $applicationConfig,
        BootstrapItemInterface $bootstrapItem,
        ConfiguratorRegistry $configuratorRegistry
    ): void {
        $configurator = $bootstrapItem->getConfigurator();

        $bootstrapFiles = [];
        /** @var PackageInterface $package */
        foreach ($applicationConfig->getPackages() as $package) {
            if (empty($package->getBootstrapDirectory())) {
                continue;
            }

            $bootstrapFiles[] = BootstrapItemInclude::normalizePath($package->getBootstrapDirectory()) . $bootstrapItem->getFileName();
        }

        $bootstrapFiles[] = $applicationConfig->getBootstrapDirectory() . $bootstrapItem->getFileName();
        $bootstrapFiles[] =
            $applicationConfig->getBootstrapDirectory() . $applicationConfig->getBootstrapEnvDirectory() .
            $bootstrapItem->getFileName();

        foreach ($bootstrapFiles as $file) {
            if (\file_exists($file)) {
                BootstrapItemInclude::include(
                    $file,
                    [$bootstrapItem->getVariableName() => $configurator]
                );
            }
        }

        $configuratorRegistry->add(\get_class($bootstrapItem), $configurator);
    }
}
