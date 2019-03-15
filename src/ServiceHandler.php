<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

use Ixocreate\Config\Config;
use Ixocreate\Contract\Application\BootstrapItemInterface;
use Ixocreate\Contract\Application\PackageInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

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
            $package->configure($configuratorRegistry);
        }

        $serviceRegistry = new ServiceRegistry();
        $serviceRegistry->add(Config::class, $this->createConfig($applicationConfig));

        foreach ($applicationConfig->getBootstrapItems() as $bootstrapItem) {
            $configuratorRegistry->get(\get_class($bootstrapItem))->registerService($serviceRegistry);
        }

        foreach ($applicationConfig->getPackages() as $package) {
            $package->addServices($serviceRegistry);
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
        $directory = $applicationConfig->getCacheDirectory();
        if (!\file_exists($directory)) {
            \mkdir($directory, 0777, true);
        }

        return $directory . "services.cache";
    }

    /**
     * @param ApplicationConfig $applicationConfig
     * @return Config
     */
    private function createConfig(ApplicationConfig $applicationConfig): Config
    {
        $mergedConfig = [];
        $configDirectories = [];
        foreach ($applicationConfig->getPackages() as $package) {
            if (!empty($package->getConfigProvider())) {
                foreach ($package->getConfigProvider() as $provider) {
                    $mergedConfig = ArrayUtils::merge($mergedConfig, (new $provider())());
                }
            }
            if (!empty($package->getConfigDirectory())) {
                $configDirectories[] = $package->getConfigDirectory();
            }
        }

        $configDirectories[] = $applicationConfig->getConfigDirectory();
        $configDirectories[] = $applicationConfig->getConfigDirectory() . $applicationConfig->getConfigEnvDirectory();
        foreach ($configDirectories as $directory) {
            if (!\is_dir($directory)) {
                continue;
            }

            $directory = \rtrim($directory, '/') . '/';

            foreach (Glob::glob($directory . "*.config.php", Glob::GLOB_BRACE, true) as $file) {
                $data = require $file;
                if (!\is_array($data)) {
                    continue;
                }

                $prefix = \mb_substr(\basename($file), 0, -11);
                $data = [$prefix => $data];
                $mergedConfig = ArrayUtils::merge($mergedConfig, $data);
            }
        }

        return new Config($mergedConfig);
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

            $bootstrapFiles[] = IncludeHelper::normalizePath($package->getBootstrapDirectory()) . $bootstrapItem->getFileName();
        }

        $bootstrapFiles[] = $applicationConfig->getBootstrapDirectory() . $bootstrapItem->getFileName();
        $bootstrapFiles[] =
            $applicationConfig->getBootstrapDirectory() . $applicationConfig->getBootstrapEnvDirectory() .
            $bootstrapItem->getFileName();

        foreach ($bootstrapFiles as $file) {
            if (\file_exists($file)) {
                IncludeHelper::include(
                    $file,
                    [$bootstrapItem->getVariableName() => $configurator]
                );
            }
        }

        $configuratorRegistry->add(\get_class($bootstrapItem), $configurator);
    }
}
