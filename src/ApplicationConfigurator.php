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

use KiwiSuite\Application\Bootstrap\BootstrapInterface;
use KiwiSuite\Application\Module\ModuleInterface;

final class ApplicationConfigurator
{
    /**
     * @var bool
     */
    private $development = true;

    /**
     * @var array
     */
    private $bootstrapQueue = [];

    /**
     * @var array
     */
    private $configurators = [];

    /**
     * @var string
     */
    private $persistCacheDirectory = "resource/application/";

    /**
     * @var string
     */
    private $cacheDirectory = "data/cache/application/";

    /**
     * @var string
     */
    private $bootstrapDirectory;

    /**
     * @var string
     */
    private $configDirectory = "config/";

    /**
     * @var array
     */
    private $modules = [];

    /**
     * ApplicationConfigurator constructor.
     * @param string $bootstrapDirectory
     */
    public function __construct(string $bootstrapDirectory)
    {
        $this->bootstrapDirectory = IncludeHelper::normalizePath($bootstrapDirectory);
    }

    /**
     * @param bool $development
     */
    public function setDevelopment(bool $development): void
    {
        $this->development = $development;
    }

    /**
     * @param string $persistCacheDirectory
     */
    public function setPersistCacheDirectory(string $persistCacheDirectory): void
    {
        $this->persistCacheDirectory = IncludeHelper::normalizePath($persistCacheDirectory);
    }

    /**
     * @param string $cacheDirectory
     */
    public function setCacheDirectory(string $cacheDirectory): void
    {
        $this->cacheDirectory = IncludeHelper::normalizePath($cacheDirectory);
    }

    /**
     * @param string $configDirectory
     */
    public function setConfigDirectory(string $configDirectory): void
    {
        $this->configDirectory = IncludeHelper::normalizePath($configDirectory);
    }

    /**
     * @param string $bootstrapItem
     */
    public function addBootstrapItem(string $bootstrapItem): void
    {
        //TODO interface check
        $this->bootstrapQueue[] = $bootstrapItem;
    }

    public function addConfiguratorItem(string $configuratorItem) : void
    {
        //TODO interface check
        $this->configurators[] = $configuratorItem;
    }

    /**
     * @param string $module
     */
    public function addModule(string $module) : void
    {
        //TODO check Interface
        $this->modules[] = $module;
    }

    /**
     * @return ApplicationConfig
     */
    public function getApplicationConfig(): ApplicationConfig
    {
        foreach ($this->modules as $moduleClass) {
            /** @var ModuleInterface $module */
            $module = new $moduleClass();

            $configurators = $module->getConfiguratorItems();
            if (!empty($configurators)) {
                foreach ($configurators as $configurator) {
                    $this->addConfiguratorItem($configurator);
                }
            }

            $bootstrapItems = $module->getBootstrapItems();
            if (!empty($bootstrapItems)) {
                foreach ($bootstrapItems as $bootstrapItem) {
                    $this->addBootstrapItem($bootstrapItem);
                }
            }
        }

        $bootstrapQueue = [];
        foreach ($this->bootstrapQueue as $bootstrapClass) {
            $bootstrapQueue[] = $bootstrapClass;

            /** @var BootstrapInterface $bootstrap */
            $bootstrap = new $bootstrapClass();
            $configurators = $bootstrap->getConfiguratorItems();
            if (empty($configurators)) {
                continue;
            }

            foreach ($configurators as $configurator) {
                $this->addConfiguratorItem($configurator);
            }
        }

        return new ApplicationConfig(
            $this->development,
            $this->configDirectory,
            $this->bootstrapDirectory,
            $this->cacheDirectory,
            $this->persistCacheDirectory,
            $bootstrapQueue,
            $this->configurators,
            $this->modules
        );
    }
}
