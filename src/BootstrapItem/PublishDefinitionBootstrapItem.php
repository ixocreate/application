<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\BootstrapItem;

use Ixocreate\Application\Publish\PublishDefinitionConfigurator;
use Ixocreate\Application\BootstrapItemInterface;
use Ixocreate\Application\ConfiguratorInterface;

/**
 * Class PublishDefinitionBootstrapItem
 *
 * @package Ixocreate\Application\BootstrapItem
 * @deprecated
 */
final class PublishDefinitionBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new PublishDefinitionConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'publishDefinition';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'publish-definition.php';
    }
}
