<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Pipe;

use Zend\Stdlib\PriorityList;

final class GroupPipeConfigurator extends RouteCollectorConfigurator
{
    /**
     * @var array
     */
    private $before = [];

    /**
     * @var array
     */
    private $after = [];

    /**
     * @var PriorityList
     */
    private $groups;

    public function __construct()
    {
        $this->groups = new PriorityList();
        parent::__construct();
    }

    /**
     * @param string $middleware
     * @param bool $prepend
     */
    public function before(string $middleware, bool $prepend = false): void
    {
        //TODO check MiddlewareInterface

        if ($prepend === true) {
            \array_unshift($this->before, $middleware);
            return;
        }

        $this->before[] = $middleware;
    }

    /**
     * @param string $middleware
     * @param bool $prepend
     */
    public function after(string $middleware, bool $prepend = false): void
    {
        //TODO check MiddlewareInterface|HandlerInterface
        if ($prepend === true) {
            \array_unshift($this->after, $middleware);
            return;
        }

        $this->after[] = $middleware;
    }

    /**
     * @return array
     */
    public function getBefore(): array
    {
        return $this->before;
    }

    /**
     * @return array
     */
    public function getAfter(): array
    {
        return $this->after;
    }

    /**
     * @param callable $callable
     */
    public function __invoke(callable $callable)
    {
        $callable($this);
    }

    /**
     * @param string $name
     * @return GroupPipeConfigurator
     */
    public function group(string $name): GroupPipeConfigurator
    {
        if ($this->groups->get($name)) {
            $groupPipeConfigurator = $this->groups->get($name);
        } else {
            $groupPipeConfigurator = new GroupPipeConfigurator();
            $this->groups->insert($name, $groupPipeConfigurator);
        }

        return $groupPipeConfigurator;
    }

    /**
     * @return RouteConfigurator[]
     */
    public function getRoutes(): array
    {
        $routes = parent::getRoutes();

        $priorityList = new PriorityList();
        /** @var RouteConfigurator $route */
        foreach ($routes as $route) {
            $priorityList->insert($route->getName(), clone $route, $route->getPriority());
        }

        /** @var GroupPipeConfigurator $group */
        foreach ($this->groups->toArray() as $group) {
            $routes = $group->getRoutes();

            $before = \array_reverse($group->getBefore());
            $after = $group->getAfter();

            /** @var RouteConfigurator $route */
            foreach ($routes as $route) {
                $route = clone $route;
                foreach ($before as $middleware) {
                    $route->before($middleware, true);
                }
                foreach ($after as $middleware) {
                    $route->after($middleware);
                }
                $priorityList->insert($route->getName(), $route, $route->getPriority());
            }
        }
        return \array_values($priorityList->toArray());
    }
}
