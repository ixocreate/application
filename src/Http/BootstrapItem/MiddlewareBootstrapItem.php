<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\BootstrapItem;

use Ixocreate\Application\Http\Middleware\MiddlewareConfigurator;
use Ixocreate\Application\BootstrapItemInterface;
use Ixocreate\Application\ConfiguratorInterface;

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
