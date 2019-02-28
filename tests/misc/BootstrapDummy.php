<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace IxocreateMisc\Application;

use Ixocreate\Contract\Application\BootstrapItemInterface;
use Ixocreate\Contract\Application\ConfiguratorInterface;

class BootstrapDummy implements BootstrapItemInterface
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
        return 'dummy';
    }
}
