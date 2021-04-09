<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Configurator\ConfiguratorInterface;

class BootstrapItemDummy implements BootstrapItemInterface
{
    public function getConfigurator(): ConfiguratorInterface
    {
        return new ConfiguratorDummy();
    }

    public function getVariableName(): string
    {
        return 'dummy';
    }

    public function getFileName(): string
    {
        return 'dummy.php';
    }
}
