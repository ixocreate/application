<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console;

use Ixocreate\Application\BootstrapItemInterface;
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
