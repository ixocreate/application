<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Uri\Middleware;

use Ixocreate\Application\Uri\ApplicationUri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\RedirectResponse;

final class ApplicationUriCheckMiddleware implements MiddlewareInterface
{
    /**
     * @var ApplicationUri
     */
    private $applicationUri;

    /**
     * ApplicationUriCheckMiddleware constructor.
     *
     * @param ApplicationUri $applicationUri
     */
    public function __construct(ApplicationUri $applicationUri)
    {
        $this->applicationUri = $applicationUri;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestUri = $request->getUri();

        if ($this->applicationUri->isValidUrl($requestUri)) {
            return $handler->handle($request);
        }

        $redirectWithPath = false;
        $redirectUri = $this->applicationUri->getMainUri();

        foreach ($this->applicationUri->getPossibleUrls() as $uri) {
            if ($requestUri->getHost() == $uri->getHost()) {
                $redirectUri = $uri;
                $redirectWithPath = true;
                break;
            }
        }

        if (!$redirectWithPath) {
            $redirectWithPath = \in_array($requestUri->getHost(), $this->applicationUri->getFullRedirectDomains());
        }

        if ($redirectWithPath) {
            $redirectUri = $redirectUri->withPath($requestUri->getPath());
            $redirectUri = $redirectUri->withQuery($requestUri->getQuery());
        }

        return new RedirectResponse($redirectUri);
    }
}
