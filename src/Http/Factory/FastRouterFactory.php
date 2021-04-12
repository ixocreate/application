<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Factory;

use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Mezzio\Router\FastRouteRouter;

final class FastRouterFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return FastRouteRouter
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        return new FastRouteRouter();
    }
}
