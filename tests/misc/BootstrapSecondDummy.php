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

class BootstrapSecondDummy implements BootstrapItemInterface
{
    public function getConfigurator(): ConfiguratorInterface
    {
        return new ConfiguratorDummy();
    }

    public function getVariableName(): string
    {
        return 'secondDummy';
    }

    public function getFileName(): string
    {
        return 'secondDummy.php';
    }
}
