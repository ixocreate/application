<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Middleware;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Configurator\ConfiguratorInterface;

final class MiddlewareBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return ConfiguratorInterface
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new MiddlewareConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'middleware';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'middleware.php';
    }
}
