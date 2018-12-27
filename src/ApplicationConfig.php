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
namespace Ixocreate\Application;

use Ixocreate\Contract\Application\BootstrapItemInterface;
use Ixocreate\Contract\Application\PackageInterface;
use Ixocreate\Contract\Application\SerializableServiceInterface;

final class ApplicationConfig implements SerializableServiceInterface
{
    private $config = [
        'development'                   => true,
        'persistCacheDirectory'         => 'resources/application/',
        'cacheDirectory'                => 'data/cache/application/',
        'bootstrapDirectory'            => 'bootstrap/',
        'configDirectory'               => 'config/',
        'bootstrapItems'                => [],
        'packages'                      => [],
    ];

    private $bootstrapItems = null;

    private $packages = null;

    /**
     * ApplicationConfig constructor.
     * @param ApplicationConfigurator $applicationConfigurator
     */
    public function __construct(ApplicationConfigurator $applicationConfigurator)
    {
        $this->config = [
            'development'                   => $applicationConfigurator->isDevelopment(),
            'persistCacheDirectory'         => $applicationConfigurator->getPersistCacheDirectory(),
            'cacheDirectory'                => $applicationConfigurator->getCacheDirectory(),
            'bootstrapDirectory'            => $applicationConfigurator->getBootstrapDirectory(),
            'configDirectory'               => $applicationConfigurator->getConfigDirectory(),
            'bootstrapItems'                => $applicationConfigurator->getBootstrapItems(),
            'packages'                      => $applicationConfigurator->getPackages(),
        ];
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
     * @return BootstrapItemInterface[]
     */
    public function getBootstrapItems() : array
    {
        if ($this->bootstrapItems === null) {
            $this->bootstrapItems = [];

            foreach ($this->config['bootstrapItems'] as $bootstrapItem) {
                $this->bootstrapItems[] = new $bootstrapItem();
            }
        }
        return $this->bootstrapItems;
    }

    /**
     * @return PackageInterface[]
     */
    public function getPackages() : array
    {
        if ($this->packages === null) {
            $this->packages = [];

            foreach ($this->config['packages'] as $packagesClass) {
                $this->packages[] = new $packagesClass();
            }
        }

        return $this->packages;
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
        $this->bootstrapItems = null;
        $this->packages = null;
    }
}
