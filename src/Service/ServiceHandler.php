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
namespace KiwiSuite\Application\Service;

use KiwiSuite\Application\ApplicationConfig;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry;
use KiwiSuite\Application\ConfiguratorItem\ServiceManagerConfiguratorItem;
use KiwiSuite\Application\IncludeHelper;
use KiwiSuite\Config\Config;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
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

        $this->handleConfiguratorItem($applicationConfig, new ServiceManagerConfiguratorItem(), $configuratorRegistry);

        foreach ($applicationConfig->getConfiguratorItems() as $configuratorItem) {
            $this->handleConfiguratorItem($applicationConfig, $configuratorItem, $configuratorRegistry);
        }

        foreach ($applicationConfig->getBootstrapQueue() as $bootstrapItem) {
            $bootstrapItem->configure($configuratorRegistry);
        }

        foreach ($applicationConfig->getModules() as $module) {
            $module->configure($configuratorRegistry);
        }

        $serviceRegistry = new ServiceRegistry();
        $serviceRegistry->addService(Config::class, $this->createConfig($applicationConfig));
        $serviceRegistry->addService(
            ServiceManagerConfig::class,
            (new ServiceManagerConfiguratorItem())->getService($configuratorRegistry->getConfiguratorByConfiguratorInterface(ServiceManagerConfiguratorItem::class))
        );

        foreach ($applicationConfig->getConfiguratorItems() as $configuratorItem) {
            $service = $configuratorItem->getService($configuratorRegistry->getConfiguratorByConfiguratorInterface(\get_class($configuratorItem)));
            $serviceRegistry->addService(\get_class($service), $service);
        }

        foreach ($applicationConfig->getBootstrapQueue() as $bootstrapItem) {
            $bootstrapItem->addServices($serviceRegistry);
        }

        foreach ($applicationConfig->getModules() as $module) {
            $module->addServices($serviceRegistry);
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
        foreach ($applicationConfig->getBootstrapQueue() as $bootstrapItem) {
            if (empty($bootstrapItem->getDefaultConfig())) {
                continue;
            }
            $mergedConfig = ArrayUtils::merge($mergedConfig, $bootstrapItem->getDefaultConfig());
        }

        foreach ($applicationConfig->getModules() as $module) {
            if (empty($module->getDefaultConfig())) {
                continue;
            }
            $mergedConfig = ArrayUtils::merge($mergedConfig, $module->getDefaultConfig());
        }

        $configDirectories = [];
        foreach ($applicationConfig->getModules() as $module) {
            $configDirectories[] = $module->getConfigDirectory();
        }

        $configDirectories[] = $applicationConfig->getConfigDirectory();
        $configDirectories[] = $applicationConfig->getConfigDirectory() . 'local/';
        foreach ($configDirectories as $directory) {
            if (!\is_dir($directory)) {
                continue;
            }
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

    private function handleConfiguratorItem(ApplicationConfig $applicationConfig, ConfiguratorItemInterface $configuratorItem, ConfiguratorRegistry $configuratorRegistry)
    {
        $configurator = $configuratorItem->getConfigurator();

        $bootstrapFiles = [];
        foreach ($applicationConfig->getModules() as $module) {
            if (empty($module->getBootstrapDirectory())) {
                continue;
            }

            $bootstrapFiles[] = IncludeHelper::normalizePath($module->getBootstrapDirectory()) . $configuratorItem->getConfiguratorFileName();
        }

        $bootstrapFiles[] = IncludeHelper::normalizePath($applicationConfig->getBootstrapDirectory()) . $configuratorItem->getConfiguratorFileName();

        foreach ($bootstrapFiles as $file) {
            if (\file_exists($file)) {
                IncludeHelper::include(
                    $file,
                    [$configuratorItem->getConfiguratorName() => $configurator]
                );
            }
        }

        $configuratorRegistry->addConfigurator($configuratorItem->getConfiguratorName(), $configurator, \get_class($configuratorItem));
    }
}
