<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Pipe;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Configurator\ConfiguratorInterface;

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
