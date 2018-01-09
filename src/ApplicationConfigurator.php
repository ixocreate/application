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

final class ApplicationConfigurator
{
    /**
     * @var bool
     */
    private $development = true;

    /**
     * @var \SplPriorityQueue
     */
    private $bootstrapQueue;

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
        $this->bootstrapQueue = new \SplPriorityQueue();

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
     * @param int $priority
     */
    public function addBootstrapItem(string $bootstrapItem, int $priority = 100): void
    {
        //TODO interface check
        $this->bootstrapQueue->insert($bootstrapItem, $priority);
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
        $bootstrapQueue = [];
        if ($this->bootstrapQueue->count() > 0) {
            $this->bootstrapQueue->rewind();
            while ($this->bootstrapQueue->valid()) {
                $bootstrapQueue[] = $this->bootstrapQueue->extract();
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
