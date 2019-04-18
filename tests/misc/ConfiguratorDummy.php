<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application;

use Ixocreate\Application\Service\Configurator\ConfiguratorInterface;
use Ixocreate\Application\Service\Registry\ServiceRegistryInterface;

class ConfiguratorDummy implements ConfiguratorInterface
{
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
    }
}
