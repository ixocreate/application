<?php
declare(strict_types=1);

namespace KiwiSuite\Application\BootstrapItem;

use KiwiSuite\Application\Publish\PublishDefinitionConfigurator;
use KiwiSuite\Contract\Application\BootstrapItemInterface;
use KiwiSuite\Contract\Application\ConfiguratorInterface;

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
