<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

use Ixocreate\Application\Bootstrap\BootstrapItemInclude;
use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Exception\InvalidArgumentException;
use Ixocreate\Application\Package\PackageInterface;
use Ixocreate\Application\ServiceManager\ServiceManagerBootstrapItem;

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
     * @var array
     */
    private $packages = [];

    private $logErrors = true;

    private $errorDisplay = true;

    private $errorDisplayIps = [];

    private $errorTemplate = null;

    private $loginTypes = [
        'credentials' => true,
        'google' => false,
        /*
        'google' => [
            'client_id' => '',
            'client_secret' => '',
            'allowed_groups' => [],
        ],
        */
    ];

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
        if (empty($directory)) {
            throw new InvalidArgumentException('Env directory must not be empty');
        }
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
     * @param bool $development
     */
    public function setLoginTypes(array $loginTypes): void
    {
        $this->loginTypes = $loginTypes;
    }

    /**
     * @return bool
     */
    public function getLoginTypes(): array
    {
        return $this->loginTypes;
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
     * @return bool
     */
    public function isErrorDisplay(): bool
    {
        return $this->errorDisplay;
    }

    /**
     * @param bool $errorDisplay
     */
    public function setErrorDisplay(bool $errorDisplay): void
    {
        $this->errorDisplay = $errorDisplay;
    }

    /**
     * @return array
     */
    public function getErrorDisplayIps(): array
    {
        return $this->errorDisplayIps;
    }

    /**
     * @param array $ips
     */
    public function setErrorDisplayIps(array $ips): void
    {
        $this->errorDisplayIps = $ips;
    }

    /**
     * @param string $ip
     */
    public function addErrorDisplayIps(string $ip): void
    {
        $this->errorDisplayIps[] = $ip;
    }

    /**
     * @return bool
     */
    public function isLogErrors(): bool
    {
        return $this->logErrors;
    }

    /**
     * @param bool $errorLogy
     */
    public function setLogErrors(bool $logErrors): void
    {
        $this->logErrors = $logErrors;
    }

    /**
     * @return null|string
     */
    public function getErrorTemplate()
    {
        return $this->errorTemplate;
    }

    /**
     * @param string|null $errorTemplate
     */
    public function setErrorTemplate(?string $errorTemplate): void
    {
        $this->errorTemplate = $errorTemplate;
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
            foreach ($bootstrapItems as $bootstrapItem) {
                $this->addBootstrapItem($bootstrapItem);
            }
        }

        return new ApplicationConfig($this);
    }

    /**
     * @param array|null $packages
     */
    private function processPackages(?array $packages): void
    {
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
