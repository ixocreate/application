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

use KiwiSuite\Application\Exception\InvalidArgumentException;

final class ApplicationConfig implements \Serializable
{
    private $config = [
        'development'                   => true,
        'persistCacheDirectory'         => 'resources/application/',
        'cacheDirectory'                => 'data/cache/application/',
        'bootstrapDirectory'            => 'bootstrap/',
        'configDirectory'               => 'config/',
        'bootstrapQueue'                => [],
    ];

    /**
     * ApplicationConfig constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (\array_key_exists('development', $config)) {
            if (!\is_bool($config['development'])) {
                throw new InvalidArgumentException(\sprintf("'%' must be boolean", "development"));
            }

            $this->config['development'] = $config['development'];
        }

        if (\array_key_exists('persistCacheDirectory', $config)) {
            if (!\is_string($config['persistCacheDirectory'])) {
                throw new InvalidArgumentException(\sprintf("'%' must be a string", "persistCacheDirectory"));
            }

            if (empty($config['persistCacheDirectory'])) {
                $config['persistCacheDirectory'] = ".";
            }

            $this->config['persistCacheDirectory'] = \rtrim($config['persistCacheDirectory'], '/') . '/';
        }

        if (\array_key_exists('cacheDirectory', $config)) {
            if (!\is_string($config['cacheDirectory'])) {
                throw new InvalidArgumentException(\sprintf("'%' must be a string", "cacheDirectory"));
            }

            if (empty($config['cacheDirectory'])) {
                $config['cacheDirectory'] = ".";
            }

            $this->config['cacheDirectory'] = \rtrim($config['cacheDirectory'], '/') . '/';
        }

        if (\array_key_exists('bootstrapDirectory', $config)) {
            if (!\is_string($config['bootstrapDirectory'])) {
                throw new InvalidArgumentException(\sprintf("'%' must be a string", "bootstrapDirectory"));
            }

            if (empty($config['bootstrapDirectory'])) {
                $config['bootstrapDirectory'] = ".";
            }

            $this->config['bootstrapDirectory'] = \rtrim($config['bootstrapDirectory'], '/') . '/';
        }

        if (\array_key_exists('configDirectory', $config)) {
            if (!\is_string($config['configDirectory'])) {
                throw new InvalidArgumentException(\sprintf("'%' must be a string", "configDirectory"));
            }

            if (empty($config['configDirectory'])) {
                $config['configDirectory'] = ".";
            }

            $this->config['configDirectory'] = \rtrim($config['configDirectory'], '/') . '/';
        }

        if (\array_key_exists('bootstrapQueue', $config)) {
            if (!\is_array($config['bootstrapQueue'])) {
                throw new InvalidArgumentException(\sprintf("'%' must be an array", "bootstrapQueue"));
            }

            $this->config['bootstrapQueue'] = \array_values($config['bootstrapQueue']);
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
