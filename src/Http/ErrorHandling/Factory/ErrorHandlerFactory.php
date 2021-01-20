<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\ErrorHandling\Factory;

use Ixocreate\Application\Http\ErrorHandling\Response\ErrorResponseGenerator;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response;
use Laminas\Stratigility\Middleware\ErrorHandler;

final class ErrorHandlerFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed|ErrorHandler
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $generator = $container->has(ErrorResponseGenerator::class)
            ? function (
                \Throwable $e,
                ServerRequestInterface $request,
                ResponseInterface $response
            ) use ($container) {
                $generator = $container->get(ErrorResponseGenerator::class);
                return $generator($e, $request, $response);
            }
        : null;

        $handler = new ErrorHandler(
            function () {
                return new Response();
            },
            $generator
        );
        $handler->attachListener(function (\Throwable $e, $request, $response) {
            \error_log($e->__toString());
        });

        return $handler;
    }
}
