<?php
namespace KiwiSuite\Application\Http\Middleware\Factory;

use Interop\Http\ServerMiddleware\MiddlewareInterface;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerInterface;
use KiwiSuite\ServiceManager\SubManager\SubManager;
use KiwiSuite\ServiceManager\SubManager\SubManagerFactoryInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerInterface;

class MiddlewareSubManagerFactory implements SubManagerFactoryInterface
{


    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return SubManagerInterface
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null): SubManagerInterface
    {
        return new SubManager(
            $container,
            $container->get("MiddlewareConfig"),
            MiddlewareInterface::class
        );
    }
}
