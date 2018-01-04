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
use KiwiSuite\Application\Bundle\BundleInterface;
use KiwiSuite\Application\Module\ModuleInterface;

final class ApplicationConfig implements \Serializable
{
    private $config = [
        'development'                   => true,
        'persistCacheDirectory'         => 'resources/application/',
        'cacheDirectory'                => 'data/cache/application/',
        'bootstrapDirectory'            => 'bootstrap/',
        'configDirectory'               => 'config/',
        'bootstrapQueue'                => [],
        'modules'                       => [],
        'bundles'                       => [],
    ];

    /**
     * @var ModuleInterface[]
     */
    private $modules;

    /**
     * @var BootstrapInterface[]
     */
    private $bootstrapQueue;

    /**
     * @var BundleInterface[]
     */
    private $bundles;

    /**
     * ApplicationConfig constructor.
     * @param bool|null $development
     * @param null|string $configDirectory
     * @param null|string $bootstrapDirectory
     * @param null|string $cacheDirectory
     * @param null|string $persistCacheDirectory
     * @param array|null $bootstrapQueue
     * @param array|null $modules
     * @param array|null $bundles
     */
    public function __construct(
        ?bool $development = null,
        ?string $configDirectory = null,
        ?string $bootstrapDirectory = null,
        ?string $cacheDirectory = null,
        ?string $persistCacheDirectory = null,
        ?array $bootstrapQueue = null,
        ?array $modules = null,
        ?array $bundles = null
    ) {
        if ($development !== null) {
            $this->config['development'] = $development;
        }

        if ($persistCacheDirectory !== null) {
            $this->config['persistCacheDirectory'] = IncludeHelper::normalizePath($persistCacheDirectory);
        }

        if ($cacheDirectory !== null) {
            $this->config['cacheDirectory'] = IncludeHelper::normalizePath($cacheDirectory);
        }

        if ($bootstrapDirectory !== null) {
            $this->config['bootstrapDirectory'] = IncludeHelper::normalizePath($bootstrapDirectory);
        }

        if ($configDirectory !== null) {
            $this->config['configDirectory'] = IncludeHelper::normalizePath($configDirectory);
        }

        if ($bootstrapQueue !== null) {
            $this->config['bootstrapQueue'] = \array_values($bootstrapQueue);
        }

        if ($modules !== null) {
            $this->config['modules'] = \array_values(\array_unique($modules));
        }

        if ($bundles !== null) {
            $this->config['bundles'] = \array_values(\array_unique($bundles));
        }
    }

    /**
     * @return bool
     */
    public function isDevelopment() : bool
    {
        return $this->config['development'];
    }

    /**
     * @return string
     */
    public function getPersistCacheDirectory() : string
    {
        return $this->config['persistCacheDirectory'];
    }

    /**
     * @return string
     */
    public function getCacheDirectory() : string
    {
        return $this->config['cacheDirectory'];
    }

    /**
     * @return string
     */
    public function getBootstrapDirectory() : string
    {
        return $this->config['bootstrapDirectory'];
    }

    /**
     * @return string
     */
    public function getConfigDirectory() : string
    {
        return $this->config['configDirectory'];
    }

    /**
     * @return BootstrapInterface[]
     */
    public function getBootstrapQueue() : array
    {
        if ($this->bootstrapQueue === null) {
            $this->bootstrapQueue = [];

            foreach ($this->config['bootstrapQueue'] as $bootstrapClass) {
                $this->bootstrapQueue[] = new $bootstrapClass();
            }
        }
        return $this->bootstrapQueue;
    }

    /**
     * @return ModuleInterface[]
     */
    public function getModules() : array
    {
        if ($this->modules === null) {
            $this->modules = [];

            foreach ($this->config['modules'] as $moduleClass) {
                $this->modules[] = new $moduleClass();
            }
        }

        return $this->modules;
    }

    /**
     * @return BundleInterface[]
     */
    public function getBundles() : array
    {
        if ($this->bundles === null) {
            $this->bundles = [];

            foreach ($this->config['bundles'] as $bundleClass) {
                $this->bundles[] = new $bundleClass();
            }
        }

        return $this->bundles;
    }

    /**
     *
     */
    public function serialize()
    {
        return \serialize($this->config);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->config = \unserialize($serialized);
        $this->bootstrapQueue = null;
        $this->modules = null;
        $this->bundles = null;
    }
}
