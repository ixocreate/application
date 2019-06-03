<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\ServiceManager;

use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\ServiceManager\Factory\AutowireFactory;

final class SubManagerConfigurator extends AbstractServiceManagerConfigurator
{
    /**
     * @var string
     */
    private $subManagerName;

    /**
     * @var string
     */
    private $validation = null;

    /**
     * ServiceManagerConfigurator constructor.
     *
     * @param string $subManagerName
     * @param string $validation
     * @param string $defaultAutowireFactory
     */
    public function __construct(
        string $subManagerName,
        string $validation = null,
        string $defaultAutowireFactory = AutowireFactory::class
    ) {
        parent::__construct($defaultAutowireFactory);

        $this->subManagerName = $subManagerName;
        $this->validation = $validation;
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @param array $only
     */
    public function addDirectory(string $directory, bool $recursive = true, array $only = []): void
    {
        if ($this->validation !== null) {
            $only[] = $this->validation;
        }
        parent::addDirectory($directory, $recursive, \array_unique($only));
    }

    /**
     * @return string
     */
    public function getSubManagerName(): string
    {
        return $this->subManagerName;
    }

    /**
     * @return string|null
     */
    public function getValidation(): ?string
    {
        return $this->validation;
    }

    /**
     * @return SubManagerConfig
     */
    public function getServiceManagerConfig(): SubManagerConfig
    {
        $this->processDirectories();
        return new SubManagerConfig($this);
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add($this->subManagerName . '::Config', $this->getServiceManagerConfig());
    }
}
