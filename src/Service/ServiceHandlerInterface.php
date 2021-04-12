<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Service;

use Ixocreate\Application\ApplicationConfig;

interface ServiceHandlerInterface
{
    public function load(ApplicationConfig $applicationConfig): ServiceRegistry;
}
