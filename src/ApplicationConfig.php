<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Package\PackageInterface;
use Ixocreate\Application\Service\SerializableServiceInterface;

final class ApplicationConfig implements SerializableServiceInterface
{
    private $config = null;

    private $bootstrapItems = null;

    private $packages = null;

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
            'configDirectory' => $applicationConfigurator->getConfigDirectory(),
            'configEnvDirectory' => $applicationConfigurator->getConfigEnvDirectory(),
            'bootstrapItems' => $applicationConfigurator->getBootstrapItems(),
            'packages' => $applicationConfigurator->getPackages(),
            'errorDisplay' => $applicationConfigurator->isErrorDisplay(),
            'errorDisplayIps' => $applicationConfigurator->getErrorDisplayIps(),
            'errorTemplate' => $applicationConfigurator->getErrorTemplate(),
        ];
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
     * @return string
     */
    public function getConfigDirectory(): string
    {
        return $this->config['configDirectory'];
    }

    /**
     * @return string
     */
    public function getConfigEnvDirectory(): string
    {
        return $this->config['configEnvDirectory'];
    }

    /**
     * @return bool
     */
    public function isErrorDisplay(): bool
    {
        return $this->config['errorDisplay'];
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
     * @param string|null $errorTemplate
     */
    public function setErrorTemplate(?string $errorTemplate): void
    {
        $this->errorTemplate = $errorTemplate;
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
