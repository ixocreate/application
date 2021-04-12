<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\ServiceManager;

use Ixocreate\Application\Exception\InvalidArgumentException;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\ServiceManager\Factory\AutowireFactory;
use Ixocreate\ServiceManager\SubManager\SubManagerInterface;

final class SubManagerConfigurator extends AbstractServiceManagerConfigurator
{
    /**
     * @var string
     */
    private $subManagerClass;

    /**
     * @var string
     */
    private $validation = null;

    /**
     * ServiceManagerConfigurator constructor.
     *
     * @param string $subManagerClass
     * @param string $validation
     * @param string $defaultAutowireFactory
     */
    public function __construct(
        string $subManagerClass,
        string $validation = null,
        string $defaultAutowireFactory = AutowireFactory::class
    ) {
        parent::__construct($defaultAutowireFactory);

        if (!\is_subclass_of($subManagerClass, SubManagerInterface::class, true)) {
            throw new InvalidArgumentException(\sprintf(
                "'%s' doesn't implement '%s'",
                $subManagerClass,
                SubManagerInterface::class
            ));
        }

        $this->subManagerClass = $subManagerClass;

        $this->validation = \forward_static_call([$subManagerClass, 'validation']);

        // TODO: remove when all SubManagers are migrated
        if ($validation !== null) {
            $this->validation = $validation;
        }
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
        $serviceRegistry->add($this->subManagerClass . '::Config', $this->getServiceManagerConfig());
    }
}
