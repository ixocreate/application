<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Publish;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Configurator\ConfiguratorInterface;

final class PublishBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new PublishConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'publish';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'publish.php';
    }
}
