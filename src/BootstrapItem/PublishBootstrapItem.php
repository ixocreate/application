<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\BootstrapItem;

use Ixocreate\Application\Publish\PublishConfigurator;
use Ixocreate\Application\BootstrapItemInterface;
use Ixocreate\Application\ConfiguratorInterface;

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
