<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

use Ixocreate\Contract\Application\BootstrapItemInterface;
use Ixocreate\Contract\Application\PackageInterface;

final class ApplicationConfigurator
{
    /**
     * @var bool
     */
    private $development = true;

    /**
     * @var array
     */
    private $bootstrapItems = [];

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
     * @var string
     */
    private $configEnvDirectory = "local/";

    /**
     * @var array
     */
    private $packages = [];

    /**
     * ApplicationConfigurator constructor.
     * @param string $bootstrapDirectory
     */
    public function __construct(string $bootstrapDirectory)
    {
        $this->bootstrapDirectory = IncludeHelper::normalizePath($bootstrapDirectory);
    }

    public function getBootstrapDirectory(): string
    {
        return $this->bootstrapDirectory;
    }

    /**
     * @param bool $development
     */
    public function setDevelopment(bool $development): void
    {
        $this->development = $development;
    }

    /**
     * @return bool
     */
    public function isDevelopment(): bool
    {
        return $this->development;
    }

    /**
     * @param string $persistCacheDirectory
     */
    public function setPersistCacheDirectory(string $persistCacheDirectory): void
    {
        $this->persistCacheDirectory = IncludeHelper::normalizePath($persistCacheDirectory);
    }

    /**
     * @return string
     */
    public function getPersistCacheDirectory(): string
    {
        return $this->persistCacheDirectory;
    }

    /**
     * @param string $cacheDirectory
     */
    public function setCacheDirectory(string $cacheDirectory): void
    {
        $this->cacheDirectory = IncludeHelper::normalizePath($cacheDirectory);
    }

    /**
     * @return string
     */
    public function getCacheDirectory(): string
    {
        return $this->cacheDirectory;
    }

    /**
     * @param string $configDirectory
     */
    public function setConfigDirectory(string $configDirectory): void
    {
        $this->configDirectory = IncludeHelper::normalizePath($configDirectory);
    }

    /**
     * @return string
     */
    public function getConfigDirectory(): string
    {
        return $this->configDirectory;
    }

    /**
     * @param string $configEnvDirectory
     */
    public function setConfigEnvDirectory(string $configEnvDirectory): void
    {
        $this->configEnvDirectory = IncludeHelper::normalizePath($configEnvDirectory);
    }

    /**
     * @return string
     */
    public function getConfigEnvDirectory(): string
    {
        return $this->configEnvDirectory;
    }

    /**
     * @param string $bootstrapItem
     */
    public function addBootstrapItem(string $bootstrapItem): void
    {
        if (!\is_subclass_of($bootstrapItem, BootstrapItemInterface::class)) {
            throw new \InvalidArgumentException($bootstrapItem . ' must implement ' . BootstrapItemInterface::class);
        }

        $this->bootstrapItems[] = $bootstrapItem;

        $this->bootstrapItems = \array_unique($this->bootstrapItems);
    }

    public function getBootstrapItems(): array
    {
        return $this->bootstrapItems;
    }

    /**
     * @param string $package
     */
    public function addPackage(string $package) : void
    {
        if (!\is_subclass_of($package, PackageInterface::class)) {
            throw new \InvalidArgumentException($package . ' must implement ' . PackageInterface::class);
        }

        $this->packages[] = $package;

        $this->packages = \array_unique($this->packages);
    }

    /**
     * @return array
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * @return ApplicationConfig
     */
    public function getApplicationConfig(): ApplicationConfig
    {
        foreach ($this->packages as $packageClass) {
            /** @var PackageInterface $package */
            $package = new $packageClass();

            $this->processPackages($package->getDependencies());
        }

        foreach ($this->packages as $packageClass) {
            /** @var PackageInterface $package */
            $package = new $packageClass();

            $bootstrapItems = $package->getBootstrapItems();
            if (!empty($bootstrapItems)) {
                foreach ($bootstrapItems as $bootstrapItem) {
                    $this->addBootstrapItem($bootstrapItem);
                }
            }
        }

        return new ApplicationConfig($this);
    }

    /**
     * @param array|null $packages
     */
    private function processPackages(?array $packages): void
    {
        if (empty($packages)) {
            return;
        }

        foreach ($packages as $item) {
            if (\in_array($item, $this->packages)) {
                continue;
            }

            /** @var PackageInterface $package */
            $package = new $item();

            $this->addPackage($item);
            $this->processPackages($package->getDependencies());
        }
    }
}
