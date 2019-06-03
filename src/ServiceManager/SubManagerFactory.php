<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\ServiceManager;

use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerInterface;

final class SubManagerFactory implements SubManagerFactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return SubManagerInterface
     */
    public function __invoke(
        ServiceManagerInterface $container,
        string $requestedName,
        array $options = null
    ): SubManagerInterface {
        /** @var SubManagerConfig $serviceManagerConfig */
        $serviceManagerConfig = $container->get($requestedName . '::Config');

        $validation = $serviceManagerConfig->getValidation();

        return new $requestedName(
            $container,
            $serviceManagerConfig,
            [],
            $validation
        );
    }
}
