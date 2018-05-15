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
namespace KiwiSuite\Application;

use KiwiSuite\Config\Config;
use KiwiSuite\Contract\Application\BootstrapItemInterface;
use KiwiSuite\Contract\Application\PackageInterface;
use KiwiSuite\ServiceManager\BootstrapItem\ServiceManagerBootstrapItem;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

final class ServiceHandler
{
    /**
     * @param ApplicationConfig $applicationConfig
     * @return ServiceRegistry
     */
    public function loadFromCache(ApplicationConfig $applicationConfig) : ServiceRegistry
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

    public function load(ApplicationConfig $applicationConfig) : ServiceRegistry
    {
        $configuratorRegistry = new ConfiguratorRegistry();

        $this->handleBootstrapItem($applicationConfig, new ServiceManagerBootstrapItem(), $configuratorRegistry);

        foreach ($applicationConfig->getBootstrapItems() as $bootstrapItem) {
            $this->handleBootstrapItem($applicationConfig, $bootstrapItem, $configuratorRegistry);
        }

        foreach ($applicationConfig->getPackages() as $package) {
            $package->configure($configuratorRegistry);
        }

        $serviceRegistry = new ServiceRegistry();
        $serviceRegistry->add(Config::class, $this->createConfig($applicationConfig));
        $configuratorRegistry->get(ServiceManagerBootstrapItem::class)->registerService($serviceRegistry);

        foreach ($applicationConfig->getBootstrapItems() as $bootstrapItem) {
            $configuratorRegistry->get(\get_class($bootstrapItem))->registerService($serviceRegistry);
        }

        foreach ($applicationConfig->getPackages() as $package) {
            $package->addServices($serviceRegistry);
        }

        return $serviceRegistry;
    }

    public function save(ApplicationConfig $applicationConfig) : void
    {
        $serviceRegistry = $this->load($applicationConfig);

        \file_put_contents(
            $this->getCacheFileName($applicationConfig),
            \serialize($serviceRegistry)
        );
    }

    /**
     * @param ApplicationConfig $applicationConfig
     * @return string
     */
    private function getCacheFileName(ApplicationConfig $applicationConfig) : string
    {
        return $applicationConfig->getPersistCacheDirectory() . "application/services.cache";
    }

    private function createConfig(ApplicationConfig $applicationConfig) : Config
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
        $configDirectories[] = $applicationConfig->getConfigDirectory() . 'local/';
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

        $bootstrapFiles[] = IncludeHelper::normalizePath($applicationConfig->getBootstrapDirectory()) . $bootstrapItem->getFileName();

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