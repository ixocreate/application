<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stratigility\MiddlewarePipe;
use Zend\Stratigility\MiddlewarePipeInterface;

final class SegmentMiddlewarePipe implements MiddlewarePipeInterface
{
    /**
     * @var MiddlewarePipe
     */
    private $middlewarePipe;

    /**
     * GroupMiddlewarePipe constructor.
     */
    public function __construct()
    {
        $this->middlewarePipe = new MiddlewarePipe();
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->middlewarePipe->process($request, $handler);
    }

    /**
     * @param MiddlewareInterface $middleware
     */
    public function pipe(MiddlewareInterface $middleware): void
    {
        $this->middlewarePipe->pipe($middleware);
    }

    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middlewarePipe->handle($request);
    }
}
