<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Application;

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
    ];

    /**
     * ApplicationConfig constructor.
     * @param bool|null $development
     * @param null|string $configDirectory
     * @param null|string $bootstrapDirectory
     * @param null|string $cacheDirectory
     * @param null|string $persistCacheDirectory
     * @param array|null $bootstrapQueue
     */
    public function __construct(?bool $development = null,
                                ?string $configDirectory = null,
                                ?string $bootstrapDirectory = null,
                                ?string $cacheDirectory = null,
                                ?string $persistCacheDirectory = null,
                                ?array $bootstrapQueue = null
    )
    {
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
    }

    /**
     * @return bool
     */
    public function isDevelopment(): bool
    {
        return $this->config['development'];
    }

    /**
     * @return string
     */
    public function getPersistCacheDirectory(): string
    {
        return $this->config['persistCacheDirectory'];
    }

    /**
     * @return string
     */
    public function getCacheDirectory(): string
    {
        return $this->config['cacheDirectory'];
    }

    /**
     * @return string
     */
    public function getBootstrapDirectory(): string
    {
        return $this->config['bootstrapDirectory'];
    }

    /**
     * @return string
     */
    public function getConfigDirectory(): string
    {
        return $this->config['configDirectory'];
    }

    /**
     * @return array
     */
    public function getBootstrapQueue(): array
    {
        return $this->config['bootstrapQueue'];
    }

    /**
     * @return array
     */
    public function getModules(): array
    {
        return $this->config['modules'];
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
    }
}
