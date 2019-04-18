<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Uri\Middleware;

use Ixocreate\Application\Uri\ApplicationUri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

final class ApplicationUriCheckMiddleware implements MiddlewareInterface
{
    /**
     * @var ApplicationUri
     */
    private $projectUri;

    /**
     * ApplicationUriCheckMiddleware constructor.
     *
     * @param ApplicationUri $projectUri
     */
    public function __construct(ApplicationUri $projectUri)
    {
        $this->projectUri = $projectUri;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->projectUri->isValidUrl($request->getUri())) {
            return $handler->handle($request);
        }

        return new RedirectResponse($this->projectUri->getMainUri());
    }
}
