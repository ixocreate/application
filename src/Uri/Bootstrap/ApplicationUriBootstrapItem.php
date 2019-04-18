<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Uri;

use Ixocreate\Application\BootstrapItemInterface;
use Ixocreate\Application\ConfiguratorInterface;

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
