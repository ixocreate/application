<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Service\Bootstrap;

use Ixocreate\Application\Service\Configurator\ConfiguratorInterface;

interface BootstrapItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator(): ConfiguratorInterface;

    /**
     * @return string
     */
    public function getVariableName() : string;

    /**
     * @return string
     */
    public function getFileName() : string;
}
