<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Service;

use Ixocreate\Application\BootstrapItemInterface;
use Ixocreate\Application\ConfiguratorInterface;

final class ServiceManagerBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return ConfiguratorInterface
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new ServiceManagerConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'serviceManager';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'servicemanager.php';
    }
}
