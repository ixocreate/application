<?php
namespace KiwiSuite\Application\Http\Route;

final class RouteConfig
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * RouteConfig constructor.
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        //TODO Checks
        $this->routes = $routes;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
