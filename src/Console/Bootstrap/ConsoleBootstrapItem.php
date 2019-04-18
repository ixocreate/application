<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console\Bootstrap;

use Ixocreate\Application\Bootstrap\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Console\ConsoleConfigurator;
use Ixocreate\Application\ConfiguratorInterface;

final class ConsoleBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new ConsoleConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'console';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'console.php';
    }
}
