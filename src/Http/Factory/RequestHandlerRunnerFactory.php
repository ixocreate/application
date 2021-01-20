<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Factory;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Http\Middleware\MiddlewareSubManager;
use Ixocreate\Application\Http\Middleware\SegmentMiddlewarePipe;
use Ixocreate\Application\Http\Pipe\PipeConfig;
use Ixocreate\Application\Http\Pipe\PipeConfigurator;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Mezzio\Middleware\ErrorResponseGenerator;
use Laminas\HttpHandlerRunner\Emitter\EmitterStack;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;

final class RequestHandlerRunnerFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return RequestHandlerRunner
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $pipeConfig = ($options !== null && isset($options[PipeConfig::class]) && $options[PipeConfig::class] instanceof PipeConfig) ? $options[PipeConfig::class] : new PipeConfig(new PipeConfigurator());
        $isDevelopment = $container->get(ApplicationConfig::class)->isDevelopment();

        $emitter = new EmitterStack();
        $emitter->push(new SapiEmitter());

        return new RequestHandlerRunner(
            $container->get(MiddlewareSubManager::class)->build(SegmentMiddlewarePipe::class, [PipeConfig::class => $pipeConfig]),
            $emitter,
            function () {
                return ServerRequestFactory::fromGlobals();
            },
            function (\Throwable $e) use ($isDevelopment) : ResponseInterface {
                $generator = new ErrorResponseGenerator($isDevelopment);
                return $generator($e, new ServerRequest(), new Response());
            }
        );
    }
}
