<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Uri;

use Ixocreate\Application\Uri\ApplicationUri;
use Ixocreate\Application\Uri\ApplicationUriConfigurator;
use Ixocreate\Application\Uri\Middleware\ApplicationUriCheckMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;

class ApplicationUriCheckMiddlewareTest extends TestCase
{
    /**
     * @covers \Ixocreate\Application\Uri\Middleware\ApplicationUriCheckMiddleware
     */
    public function testProcess()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');

        $config = new ApplicationUri($configurator);

        $middleware = new ApplicationUriCheckMiddleware($config);

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

        $request = new ServerRequest([], [], new \Laminas\Diactoros\Uri('https://project-uri-something.test'));
        $response = $middleware->process($request, $requestHandler);
        $this->assertNull($requestHandler->getRequest());
        $this->assertInstanceOf(Response\RedirectResponse::class, $response);


        $request = new ServerRequest([], [], new \Laminas\Diactoros\Uri('https://project-uri.test'));
        $response = $middleware->process($request, $requestHandler);
        $this->assertEquals($request, $requestHandler->getRequest());
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testFullRedirectDomains()
    {
        $config = $this->createMock(ApplicationUri::class);
        $config
            ->method('isValidUrl')
            ->willReturn(false);
        $config
            ->method('getMainUri')
            ->willReturn(new Uri('https://main-url.test'));
        $config
            ->method('getPossibleUrls')
            ->willReturn([]);
        $config
            ->method('getFullRedirectDomains')
            ->willReturn(['redirect-uri.test']);

        $uri = new Uri('https://redirect-uri.test/withPath?with=params');
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getUri')
            ->willReturn($uri);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->never())
            ->method('handle');

        $middleware = new ApplicationUriCheckMiddleware($config);
        $response = $middleware->process($request, $handler);

        $this->assertInstanceOf(Response\RedirectResponse::class, $response);
        $this->assertEquals(['https://main-url.test/withPath?with=params'], $response->getHeader('location'));
    }

    public function testPossibleUrls()
    {
        $config = $this->createMock(ApplicationUri::class);
        $config
            ->method('isValidUrl')
            ->willReturn(false);
        $config
            ->method('getMainUri')
            ->willReturn(new Uri('https://main-url.test'));
        $config
            ->method('getPossibleUrls')
            ->willReturn([new Uri('https://alternative-url.test')]);
        $config
            ->method('getFullRedirectDomains')
            ->willReturn([]);

        $uri = new Uri('http://alternative-url.test/withPath?with=params');
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->method('getUri')
            ->willReturn($uri);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->never())
            ->method('handle');

        $middleware = new ApplicationUriCheckMiddleware($config);
        $response = $middleware->process($request, $handler);

        $this->assertInstanceOf(Response\RedirectResponse::class, $response);
        $this->assertEquals(['https://alternative-url.test/withPath?with=params'], $response->getHeader('location'));
    }
}
