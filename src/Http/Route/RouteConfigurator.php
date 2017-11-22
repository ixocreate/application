<?php
namespace KiwiSuite\Application\Http\Route;

use KiwiSuite\Application\Exception\InvalidArgumentException;

final class RouteConfigurator
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @param string $path
     * @param array $middleware
     * @param string $name
     */
    public function addGet(string $path, array $middleware, string $name): void
    {
        $this->addRoute($path, $middleware, $name, ['GET']);
    }

    /**
     * @param string $path
     * @param array $middleware
     * @param string $name
     */
    public function addPost(string $path, array $middleware, string $name): void
    {
        $this->addRoute($path, $middleware, $name, ['POST']);
    }

    /**
     * @param string $path
     * @param array $middleware
     * @param string $name
     */
    public function addDelete(string $path, array $middleware, string $name): void
    {
        $this->addRoute($path, $middleware, $name, ['DELETE']);
    }

    /**
     * @param string $path
     * @param array $middleware
     * @param string $name
     */
    public function addPut(string $path, array $middleware, string $name): void
    {
        $this->addRoute($path, $middleware, $name, ['PUT']);
    }

    /**
     * @param string $path
     * @param array $middleware
     * @param string $name
     */
    public function addPatch(string $path, array $middleware, string $name): void
    {
        $this->addRoute($path, $middleware, $name, ['PATCH']);
    }

    /**
     * @param string $path
     * @param array $middleware
     * @param string $name
     * @param array|null $methods
     */
    public function addRoute(string $path, array $middleware, string $name, array $methods = null): void
    {
        if ($methods !== null) {
            $methods = array_values($methods);
            foreach ($methods as $method) {
                if (!in_array($method, ['GET', 'POST', 'DELETE', 'PUT', 'PATCH'])) {
                    throw new InvalidArgumentException("'\$methods' must be an array of valid methods (GET, POST, DELETE, PUT, PATCH)");
                }
            }
        }

        $this->routes[] = [
            'path' => $path,
            'middleware' => $middleware,
            'name' => $name,
            'methods' => $methods,
        ];
    }

    /**
     * @return RouteConfig
     */
    public function getRouteConfig(): RouteConfig
    {
        return new RouteConfig($this->routes);
    }
}
