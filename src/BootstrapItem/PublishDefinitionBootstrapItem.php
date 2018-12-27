<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\BootstrapItem;

use Ixocreate\Application\Publish\PublishDefinitionConfigurator;
use Ixocreate\Contract\Application\BootstrapItemInterface;
use Ixocreate\Contract\Application\ConfiguratorInterface;

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
        return 'publish_definition.php';
    }
}
