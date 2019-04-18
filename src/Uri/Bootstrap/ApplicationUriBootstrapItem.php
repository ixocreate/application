<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Uri\Bootstrap;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\ConfiguratorInterface;
use Ixocreate\Application\Uri\ApplicationUriConfigurator;

final class ApplicationUriBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return ConfiguratorInterface
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new ApplicationUriConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'applicationUri';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'application-uri.php';
    }
}
