<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Bootstrap;

use Ixocreate\Application\Http\Pipe\PipeConfigurator;
use Ixocreate\Application\Service\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Service\Configurator\ConfiguratorInterface;

final class PipeBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return ConfiguratorInterface
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new PipeConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'pipe';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'pipe.php';
    }
}
