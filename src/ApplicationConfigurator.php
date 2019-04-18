<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

use Ixocreate\Application\Service\ServiceManagerBootstrapItem;

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
    private $persistCacheDirectory = "resources/generated/application/";

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
    private $bootstrapEnvDirectory = "local/";

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
     *
     * @param string $bootstrapDirectory
     */
    public function __construct(string $bootstrapDirectory)
    {
        $this->bootstrapDirectory = BootstrapItemInclude::normalizePath($bootstrapDirectory);
    }

    public function getBootstrapDirectory(): string
    {
        return $this->bootstrapDirectory;
    }

    /**
     * @param string $directory
     */
    public function setBootstrapEnvDirectory(string $directory): void
    {
        $this->bootstrapEnvDirectory = BootstrapItemInclude::normalizePath($directory);
    }

    /**
     * @return string
     */
    public function getBootstrapEnvDirectory(): string
    {
        return $this->bootstrapEnvDirectory;
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
        $this->persistCacheDirectory = BootstrapItemInclude::normalizePath($persistCacheDirectory);
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
        $this->cacheDirectory = BootstrapItemInclude::normalizePath($cacheDirectory);
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
        $this->configDirectory = BootstrapItemInclude::normalizePath($configDirectory);
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
        $this->configEnvDirectory = BootstrapItemInclude::normalizePath($configEnvDirectory);
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
    public function addPackage(string $package): void
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

        $this->addBootstrapItem(ServiceManagerBootstrapItem::class);

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
