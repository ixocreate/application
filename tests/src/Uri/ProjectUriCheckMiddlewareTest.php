<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\ProjectUri;

use Ixocreate\Application\Uri\ApplicationUri;
use Ixocreate\Application\Uri\ApplicationUriConfigurator;
use Ixocreate\Application\Uri\Middleware\ApplicationUriCheckMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class ProjectUriCheckMiddlewareTest extends TestCase
{
    /**
     * @covers \Ixocreate\Application\Uri\Middleware\ApplicationUriCheckMiddleware
     */
    public function testProcess()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');

        $projectUri = new ApplicationUri($configurator);

        $middleware = new ApplicationUriCheckMiddleware($projectUri);

        $requestHandler = new class() implements RequestHandlerInterface {
            private $request;

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $this->request = $request;
                return new Response();
            }

            public function getRequest()
            {
                return $this->request;
            }
        };

        $request = new ServerRequest([], [], new \Zend\Diactoros\Uri('https://project-uri-something.test'));
        $response = $middleware->process($request, $requestHandler);
        $this->assertNull($requestHandler->getRequest());
        $this->assertInstanceOf(Response\RedirectResponse::class, $response);

        $request = new ServerRequest([], [], new \Zend\Diactoros\Uri('https://project-uri.test'));
        $response = $middleware->process($request, $requestHandler);
        $this->assertEquals($request, $requestHandler->getRequest());
        $this->assertInstanceOf(Response::class, $response);
    }
}
