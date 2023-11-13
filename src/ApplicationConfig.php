<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Package\BootInterface;
use Ixocreate\Application\Package\PackageInterface;
use Ixocreate\Application\Service\SerializableServiceInterface;

final class ApplicationConfig implements SerializableServiceInterface
{
    private $config;

    private $bootstrapItems = null;

    private $packages = null;

    private $bootPackages = null;

    /**
     * ApplicationConfig constructor.
     *
     * @param ApplicationConfigurator $applicationConfigurator
     */
    public function __construct(ApplicationConfigurator $applicationConfigurator)
    {
        $this->config = [
            'development' => $applicationConfigurator->isDevelopment(),
            'persistCacheDirectory' => $applicationConfigurator->getPersistCacheDirectory(),
            'cacheDirectory' => $applicationConfigurator->getCacheDirectory(),
            'bootstrapDirectory' => $applicationConfigurator->getBootstrapDirectory(),
            'bootstrapEnvDirectory' => $applicationConfigurator->getBootstrapEnvDirectory(),
            'bootstrapItems' => $applicationConfigurator->getBootstrapItems(),
            'packages' => $applicationConfigurator->getPackages(),
            'logErrors' => $applicationConfigurator->isLogErrors(),
            'errorDisplay' => $applicationConfigurator->isErrorDisplay(),
            'errorDisplayIps' => $applicationConfigurator->getErrorDisplayIps(),
            'errorTemplate' => $applicationConfigurator->getErrorTemplate(),
            'bootPackages' => [],
        ];

        $this->initPackages();

        $this->bootPackages = [];
        foreach ($this->packages as $package) {
            if ($package instanceof BootInterface) {
                $packageClass = \get_class($package);
                $this->config['bootPackages'][] = $packageClass;
                $this->bootPackages[$packageClass] = $package;
            }
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
    public function getBootstrapEnvDirectory(): string
    {
        return $this->config['bootstrapEnvDirectory'];
    }

    /**
     * @return bool
     */
    public function isErrorDisplay(): bool
    {
        return $this->config['errorDisplay'];
    }

    /**
     * @return bool
     */
    public function isLogErrors(): bool
    {
        return $this->config['logErrors'];
    }

    /**
     * @return array
     */
    public function errorDisplayIps(): array
    {
        return $this->config['errorDisplayIps'];
    }

    /**
     * @return null|string
     */
    public function errorTemplate()
    {
        return $this->config['errorTemplate'];
    }

    /**
     * @return BootstrapItemInterface[]
     */
    public function getBootstrapItems(): array
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
    public function getPackages(): array
    {
        if ($this->packages === null) {
            $this->initPackages();
        }

        return $this->packages;
    }

    /**
     * @return BootInterface[]
     */
    public function getBootPackages(): array
    {
        if ($this->bootPackages === null) {
            $this->bootPackages = [];
            foreach ($this->config['bootPackages'] as $packagesClass) {
                $this->bootPackages[$packagesClass] = new $packagesClass();
            }
        }

        return $this->bootPackages;
    }

    private function initPackages()
    {
        $this->packages = [];
        foreach ($this->config['packages'] as $packagesClass) {
            $package = new $packagesClass();
            $this->packages[] = $package;
        }
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
