<?php
declare(strict_types=1);

namespace KiwiSuite\Application\BootstrapItem;

use KiwiSuite\Application\Publish\PublishConfigurator;
use KiwiSuite\Contract\Application\BootstrapItemInterface;
use KiwiSuite\Contract\Application\ConfiguratorInterface;

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
