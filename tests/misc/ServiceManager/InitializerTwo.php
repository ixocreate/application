<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application\ServiceManager;

use Ixocreate\ServiceManager\InitializerInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

class InitializerTwo implements InitializerInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $instance
     * @return void
     */
    public function __invoke(ServiceManagerInterface $container, $instance): void
    {
    }
}
