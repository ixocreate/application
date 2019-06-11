<?php

declare(strict_types=1);

namespace Ixocreate\Application\Package;

use Ixocreate\ServiceManager\ServiceManagerInterface;

interface BootInterface
{
    /**
     * @param ServiceManagerInterface $serviceManager
     */
    public function boot(ServiceManagerInterface $serviceManager): void;
}
