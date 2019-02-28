<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateMisc\Application;

use Ixocreate\Contract\Application\ConfiguratorInterface;
use Ixocreate\Contract\Application\ServiceRegistryInterface;

class ConfiguratorDummy implements ConfiguratorInterface
{
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
    }
}
