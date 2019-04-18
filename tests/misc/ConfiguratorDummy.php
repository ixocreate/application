<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application;

use Ixocreate\Application\ConfiguratorInterface;
use Ixocreate\Application\ServiceRegistryInterface;

class ConfiguratorDummy implements ConfiguratorInterface
{
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
    }
}
