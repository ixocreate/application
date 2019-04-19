<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Pipe;

use Zend\Stdlib\PriorityList;

class RouteCollectorConfigurator
{
    /**
     * @var PriorityList
     */
    private $routes;

    /**
     * RouteCollectorConfigurator constructor.
     */
    public function __construct()
    {
        $this->routes = new PriorityList();
    }

    /**
     * @param RouteConfigurator $routeConfigurator
     * @return RouteConfigurator
     */
    public function route(RouteConfigurator $routeConfigurator): RouteConfigurator
    {
        $this->routes->insert($routeConfigurator->getName(), $routeConfigurator, $routeConfigurator->getPriority());

        return $routeConfigurator;
    }

    /**
     * @param string $path
     * @param string $action
     * @param string $name
     * @param int|null $routePriority
     * @return RouteConfigurator
     */
    public function any(string $path, string $action, string $name, int $routePriority = null): RouteConfigurator
    {
        $routeConfigurator = new RouteConfigurator($path, $action, $name);
        $routeConfigurator->enableDelete();
        $routeConfigurator->enableGet();
        $routeConfigurator->enablePost();
        $routeConfigurator->enablePatch();
        $routeConfigurator->enableDelete();

        if ($routePriority !== null) {
            $routeConfigurator->setPriority($routePriority);
        }

        return $this->route($routeConfigurator);
    }

    /**
     * @param string $path
     * @param string $action
     * @param string $name
     * @param int|null $routePriority
     * @return RouteConfigurator
     */
    public function get(string $path, string $action, string $name, int $routePriority = null): RouteConfigurator
    {
        $routeConfigurator = new RouteConfigurator($path, $action, $name);
        $routeConfigurator->enableGet();

        if ($routePriority !== null) {
            $routeConfigurator->setPriority($routePriority);
        }

        return $this->route($routeConfigurator);
    }

    /**
     * @param string $path
     * @param string $action
     * @param string $name
     * @param int|null $routePriority
     * @return RouteConfigurator
     */
    public function post(string $path, string $action, string $name, int $routePriority = null): RouteConfigurator
    {
        $routeConfigurator = new RouteConfigurator($path, $action, $name);
        $routeConfigurator->enablePost();

        if ($routePriority !== null) {
            $routeConfigurator->setPriority($routePriority);
        }

        return $this->route($routeConfigurator);
    }

    /**
     * @param string $path
     * @param string $action
     * @param string $name
     * @param int|null $routePriority
     * @return RouteConfigurator
     */
    public function patch(string $path, string $action, string $name, int $routePriority = null): RouteConfigurator
    {
        $routeConfigurator = new RouteConfigurator($path, $action, $name);
        $routeConfigurator->enablePatch();

        if ($routePriority !== null) {
            $routeConfigurator->setPriority($routePriority);
        }

        return $this->route($routeConfigurator);
    }

    /**
     * @param string $path
     * @param string $action
     * @param string $name
     * @param int|null $routePriority
     * @return RouteConfigurator
     */
    public function put(string $path, string $action, string $name, int $routePriority = null): RouteConfigurator
    {
        $routeConfigurator = new RouteConfigurator($path, $action, $name);
        $routeConfigurator->enablePut();

        if ($routePriority !== null) {
            $routeConfigurator->setPriority($routePriority);
        }

        return $this->route($routeConfigurator);
    }

    /**
     * @param string $path
     * @param string $action
     * @param string $name
     * @param int|null $routePriority
     * @return RouteConfigurator
     */
    public function delete(string $path, string $action, string $name, int $routePriority = null): RouteConfigurator
    {
        $routeConfigurator = new RouteConfigurator($path, $action, $name);
        $routeConfigurator->enableDelete();

        if ($routePriority !== null) {
            $routeConfigurator->setPriority($routePriority);
        }

        return $this->route($routeConfigurator);
    }

    /**
     * @param string $name
     * @return RouteConfigurator
     */
    public function getRoute(string $name): RouteConfigurator
    {
        return $this->routes->get($name);
    }

    /**
     * @return RouteConfigurator[]
     */
    public function getRoutes(): array
    {
        return \array_values($this->routes->toArray());
    }
}
