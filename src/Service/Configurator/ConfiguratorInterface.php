<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Service\Configurator;

use Ixocreate\Application\Service\Registry\ServiceRegistryInterface;

interface ConfiguratorInterface
{
    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void;
}
