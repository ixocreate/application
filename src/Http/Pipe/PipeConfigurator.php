<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Pipe;

use Ixocreate\Application\Configurator\ConfiguratorInterface;
use Ixocreate\Application\Http\Pipe\Config\DispatchingPipeConfig;
use Ixocreate\Application\Http\Pipe\Config\MiddlewareConfig;
use Ixocreate\Application\Http\Pipe\Config\RoutingPipeConfig;
use Ixocreate\Application\Http\Pipe\Config\SegmentConfig;
use Ixocreate\Application\Http\Pipe\Config\SegmentPipeConfig;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Stdlib\PriorityList;

final class PipeConfigurator extends RouteCollectorConfigurator implements ConfiguratorInterface
{
    public const PRIORITY_PRE_ROUTING = 1000000;

    public const PRIORITY_POST_ROUTING = 499999;

    public const PRIORITY_POST_DISPATCHING = 999;

    private const PRIORITY_ROUTING = 500000;

    private const PRIORITY_DISPATCHING = 1000;

    /**
     * @var PriorityList
     */
    private $middlewares;

    /**
     * @var PriorityList
     */
    private $segments;

    /**
     * @var PriorityList
     */
    private $segmentPipes;

    /**
     * @var PriorityList
     */
    private $groups;

    /**
     * @var string
     */
    private $router = FastRouteRouter::class;

    public function __construct()
    {
        $this->segments = new PriorityList();
        $this->segmentPipes = new PriorityList();
        $this->groups = new PriorityList();

        $this->middlewares = new PriorityList();

        parent::__construct();
    }

    /**
     * @param callable $callable
     */
    public function __invoke(callable $callable)
    {
        $callable($this);
    }

    /**
     * @param int $priority
     * @throws \Exception
     */
    private function validatePriority(int $priority): void
    {
        if ($priority === self::PRIORITY_DISPATCHING || $priority === self::PRIORITY_ROUTING) {
            //TODO Exception
            throw new \Exception("invalid priority");
        }
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
     * @param string $segment
     * @param int $priority
     * @return PipeConfigurator
     */
    public function segment(string $segment, int $priority = self::PRIORITY_PRE_ROUTING): PipeConfigurator
    {
        if ($priority <= self::PRIORITY_ROUTING) {
            //TODO Exception
        }

        if ($this->segments->get($segment)) {
            $pipeConfigurator = $this->segments->get($segment);
        } else {
            $pipeConfigurator = new PipeConfigurator();

            $this->segments->insert($segment, $pipeConfigurator, $priority);
        }

        return $pipeConfigurator;
    }

    public function segmentPipe(string $provider, int $priority = self::PRIORITY_PRE_ROUTING): PipeConfigurator
    {
        if ($priority <= self::PRIORITY_ROUTING) {
            //TODO Exception
        }

        if ($this->segmentPipes->get($provider)) {
            $pipeConfigurator = $this->segmentPipes->get($provider);
        } else {
            $pipeConfigurator = new PipeConfigurator();

            $this->segmentPipes->insert($provider, $pipeConfigurator, $priority);
        }

        return $pipeConfigurator;
    }

    public function pipe(string $middleware, int $priority = self::PRIORITY_PRE_ROUTING): void
    {
        $this->validatePriority($priority);
        $this->middlewares->insert($middleware, $middleware, $priority);
    }

    /**
     * @param string $router
     */
    public function setRouter(string $router): void
    {
        $this->router = $router;
    }

    /**
     * @return string
     */
    public function getRouter(): string
    {
        return $this->router;
    }

    public function getRoutes(): array
    {
        $priorityList = new PriorityList();

        $routes = parent::getRoutes();
        /** @var RouteConfigurator $routeConfigurator */
        foreach ($routes as $routeConfigurator) {
            $priorityList->insert($routeConfigurator->getName(), $routeConfigurator, $routeConfigurator->getPriority());
        }

        /** @var GroupPipeConfigurator $groupConfigurator */
        foreach ($this->groups->toArray() as $groupConfigurator) {
            $before = \array_reverse($groupConfigurator->getBefore());
            $after = $groupConfigurator->getAfter();

            foreach ($groupConfigurator->getRoutes() as $routeConfigurator) {
                $routeConfigurator = clone $routeConfigurator;
                foreach ($before as $middleware) {
                    $routeConfigurator->before($middleware, true);
                }
                foreach ($after as $middleware) {
                    $routeConfigurator->after($middleware);
                }

                $priorityList->insert($routeConfigurator->getName(), $routeConfigurator, $routeConfigurator->getPriority());
            }
        }

        $routes = [];
        foreach ($priorityList->toArray() as $routeConfigurator) {
            $routes[] =  [
                'name' => $routeConfigurator->getName(),
                'path' => $routeConfigurator->getPath(),
                'pipe' => $routeConfigurator->getPipe(),
                'methods' => $routeConfigurator->getMethods(),
                'options' => $routeConfigurator->getOptions(),
            ];
        }

        return $routes;
    }

    public function getMiddlewarePipe(): array
    {
        $priorityList = new PriorityList();
        $priorityList->insert('routing', new RoutingPipeConfig(), self::PRIORITY_ROUTING);
        $priorityList->insert('dispatching', new DispatchingPipeConfig(), self::PRIORITY_DISPATCHING);

        foreach ($this->middlewares->toArray(PriorityList::EXTR_BOTH) as $name => $item) {
            $priorityList->insert('middleware:' . $item['data'], new MiddlewareConfig($item['data']), $item['priority']);
        }

        foreach ($this->segments->toArray(PriorityList::EXTR_BOTH) as $segment => $item) {
            $priorityList->insert('segment:' . $segment, new SegmentConfig($segment, new PipeConfig($item['data'])), $item['priority']);
        }

        foreach ($this->segmentPipes->toArray(PriorityList::EXTR_BOTH) as $segmentPipe => $item) {
            $priorityList->insert('segmentPipe:' . $segmentPipe, new SegmentPipeConfig($segmentPipe, new PipeConfig($item['data'])), $item['priority']);
        }

        return \array_values($priorityList->toArray());
    }

    /**
     * @return array
     */
    public function getBreadcrumb(): array
    {
        return $this->breadcrumb;
    }

    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add(PipeConfig::class, new PipeConfig($this));
    }
}
