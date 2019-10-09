<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Middleware\Factory;

use Ixocreate\Application\Http\Middleware\MiddlewareSubManager;
use Ixocreate\Application\Http\Middleware\SegmentMiddlewarePipe;
use Ixocreate\Application\Http\Pipe\Config\DispatchingPipeConfig;
use Ixocreate\Application\Http\Pipe\Config\MiddlewareConfig;
use Ixocreate\Application\Http\Pipe\Config\RoutingPipeConfig;
use Ixocreate\Application\Http\Pipe\Config\SegmentConfig;
use Ixocreate\Application\Http\Pipe\Config\SegmentPipeConfig;
use Ixocreate\Application\Http\Pipe\PipeConfig;
use Ixocreate\Application\Http\SegmentProviderInterface;
use Ixocreate\Application\Uri\ApplicationUri;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Uri;
use Zend\Expressive\MiddlewareContainer;
use Zend\Expressive\MiddlewareFactory;
use Zend\Expressive\Router\Middleware\DispatchMiddleware;
use Zend\Expressive\Router\Middleware\RouteMiddleware;
use Zend\Expressive\Router\RouteCollector;
use Zend\Stratigility\Middleware\PathMiddlewareDecorator;

final class SegmentMiddlewareFactory implements FactoryInterface
{
    /**
     * @var MiddlewareFactory
     */
    private $middlewareFactory;

    /**
     * @var MiddlewareSubManager
     */
    private $middlewareSubManager;

    /**
     * @var ServiceManagerInterface
     */
    private $container;

    /**
     * @var ApplicationUri
     */
    private $projectUri;

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $this->container = $container;
        $this->middlewareSubManager = $container->get(MiddlewareSubManager::class);
        $this->middlewareFactory = new MiddlewareFactory(new MiddlewareContainer($this->middlewareSubManager));
        $this->projectUri = $container->get(ApplicationUri::class);

        if ($options === null) {
            //todo exception
        }

        if (!isset($options[PipeConfig::class]) || !($options[PipeConfig::class] instanceof PipeConfig)) {
            //todo exception
        }

        $segmentMiddlewarePipe = new SegmentMiddlewarePipe();

        /** @var PipeConfig $pipeConfig */
        $pipeConfig = $options[PipeConfig::class];

        foreach ($pipeConfig->getMiddlewarePipe() as $itemPipeConfig) {
            switch (\get_class($itemPipeConfig)) {
                case MiddlewareConfig::class:
                    $segmentMiddlewarePipe->pipe($this->createMiddleware($itemPipeConfig));
                    break;
                case SegmentConfig::class:
                    $segmentMiddlewarePipe->pipe($this->createSegmentMiddleware($itemPipeConfig));
                    break;
                case SegmentPipeConfig::class:
                    $segmentMiddlewarePipe->pipe($this->createSegmentPipeMiddleware($itemPipeConfig));
                    break;
                case RoutingPipeConfig::class:
                    $segmentMiddlewarePipe->pipe($this->createRoutingMiddleware($pipeConfig));
                    break;
                case DispatchingPipeConfig::class:
                    $segmentMiddlewarePipe->pipe($this->createDispatchingMiddleware());
                    break;
            }
        }
        return $segmentMiddlewarePipe;
    }

    /**
     * @param MiddlewareConfig $middlewareConfig
     * @return MiddlewareInterface
     */
    private function createMiddleware(MiddlewareConfig $middlewareConfig): MiddlewareInterface
    {
        return $this->middlewareFactory->lazy($middlewareConfig->middleware());
    }

    /**
     * @param SegmentConfig $segmentConfig
     * @return MiddlewareInterface
     */
    private function createSegmentMiddleware(SegmentConfig $segmentConfig): MiddlewareInterface
    {
        return $this->middlewareFactory->callable(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($segmentConfig) {
            $uri = new Uri($segmentConfig->segment());
            if (!$this->checkUri($uri, $request)) {
                return $handler->handle($request);
            }

            $segmentMiddleware = $this->container
                ->get(MiddlewareSubManager::class)
                ->build(
                    SegmentMiddlewarePipe::class,
                    [
                        PipeConfig::class => $segmentConfig->pipeConfig(),
                    ]
                );

            $pathMiddlewareDecorator = new PathMiddlewareDecorator($uri->getPath(), $segmentMiddleware);
            return $pathMiddlewareDecorator->process($request, $handler);
        });
    }

    /**
     * @param SegmentPipeConfig $pipeConfig
     * @return MiddlewareInterface
     */
    private function createSegmentPipeMiddleware(SegmentPipeConfig $pipeConfig): MiddlewareInterface
    {
        return $this->middlewareFactory->callable(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($pipeConfig) {

            /** @var SegmentProviderInterface $provider */
            $provider = $this->container->get($pipeConfig->provider());

            $uri = new Uri($provider->getSegment());
            if (!$this->checkUri($uri, $request)) {
                return $handler->handle($request);
            }

            $segmentMiddleware = $this->container
                ->get(MiddlewareSubManager::class)
                ->build(
                    SegmentMiddlewarePipe::class,
                    [
                        PipeConfig::class => $pipeConfig->pipeConfig(),
                    ]
                );

            $pathMiddlewareDecorator = new PathMiddlewareDecorator($uri->getPath(), $segmentMiddleware);
            return $pathMiddlewareDecorator->process($request, $handler);
        });
    }

    /**
     * @param Uri $uri
     * @param ServerRequestInterface $request
     * @return bool
     */
    private function checkUri(Uri $uri, ServerRequestInterface $request): bool
    {
        if (!empty($uri->getScheme()) && $uri->getScheme() !== $request->getUri()->getScheme()) {
            return false;
        }

        if (!empty($uri->getHost()) && $uri->getHost() !== $request->getUri()->getHost()) {
            return false;
        }

        if (!empty($uri->getPort()) && $uri->getPort() !== $request->getUri()->getPort()) {
            return false;
        }

        return true;
    }

    /**
     * @param PipeConfig $pipeConfig
     * @return MiddlewareInterface
     */
    private function createRoutingMiddleware(PipeConfig $pipeConfig): MiddlewareInterface
    {
        return $this->middlewareFactory->callable(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($pipeConfig) {
            $routeCollector = new RouteCollector($this->container->get($pipeConfig->router()));
            foreach ($pipeConfig->getRoutes() as $route) {
                $expressiveRoute = $routeCollector->route(
                    $route['path'],
                    $this->middlewareFactory->pipeline($route['pipe']),
                    $route['methods'],
                    $route['name']
                );
                $expressiveRoute->setOptions($route['options']);
            }

            $routeMiddleware = new RouteMiddleware($this->container->get($pipeConfig->router()));

            return $routeMiddleware->process($request, $handler);
        });
    }

    /**
     * @return MiddlewareInterface
     */
    private function createDispatchingMiddleware(): MiddlewareInterface
    {
        return $this->middlewareFactory->lazy(DispatchMiddleware::class);
    }
}
