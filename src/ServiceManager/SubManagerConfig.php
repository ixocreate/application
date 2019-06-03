<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\ServiceManager;

use Ixocreate\Application\Service\SerializableServiceInterface;
use Ixocreate\ServiceManager\ServiceManagerConfigInterface;

final class SubManagerConfig implements ServiceManagerConfigInterface, SerializableServiceInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * ServiceManagerConfig constructor.
     *
     * @param SubManagerConfigurator $serviceManagerConfigurator
     */
    public function __construct(SubManagerConfigurator $serviceManagerConfigurator)
    {
        $this->config['factories'] = $serviceManagerConfigurator->getFactories();
        $this->config['delegators'] = $serviceManagerConfigurator->getDelegators();
        $this->config['lazyServices'] = $serviceManagerConfigurator->getLazyServices();
        $this->config['initializers'] = $serviceManagerConfigurator->getInitializers();
        $this->config['subManagerName'] = $serviceManagerConfigurator->getSubManagerClass();
        $this->config['validation'] = $serviceManagerConfigurator->getValidation();

        $this->config['namedServices'] = [];

        foreach (\array_keys($this->config['factories']) as $service) {
            if (!\is_subclass_of($service, NamedServiceInterface::class, true)) {
                continue;
            }
            $this->config['namedServices'][\forward_static_call([$service, 'serviceName'])] = $service;
        }
    }

    /**
     * @return array
     */
    public function getFactories(): array
    {
        return $this->config['factories'];
    }

    /**
     * @return array
     */
    public function getDisabledSharing(): array
    {
        return $this->config['disabledSharing'];
    }

    /**
     * @return array
     */
    public function getDelegators(): array
    {
        return $this->config['delegators'];
    }

    /**
     * @return array
     */
    public function getInitializers(): array
    {
        return $this->config['initializers'];
    }

    /**
     * @return array
     */
    public function getLazyServices(): array
    {
        return $this->config['lazyServices'];
    }

    /**
     * @return array
     */
    public function getNamedServices(): array
    {
        return $this->config['namedServices'];
    }

    public function getSubManagerName(): string
    {
        return $this->config['subManagerName'];
    }

    public function getValidation(): ?string
    {
        return $this->config['validation'];
    }

    /**
     * @return string
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
