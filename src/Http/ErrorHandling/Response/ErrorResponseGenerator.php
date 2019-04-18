<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\ApplicationHttp\ErrorHandling\Response;

use Ixocreate\Template\Package\Renderer;
use Throwable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\Utils;

final class ErrorResponseGenerator
{
    /**
     * Name of the template to render.
     *
     * @var string
     */
    private $template;

    /**
     * @var Renderer|null
     */
    private $renderer;

    public const TEMPLATE_DEFAULT = 'error::error';

    /**
     * ErrorResponseGenerator constructor.
     * @param Renderer|null $renderer
     * @param string $template
     */
    public function __construct(
        Renderer $renderer = null,
        string $template = self::TEMPLATE_DEFAULT
    ) {
        $this->renderer  = $renderer;
        $this->template  = $template;
    }

    /**
     * @param Throwable $e
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        Throwable $e,
        ServerRequestInterface $request,
        ResponseInterface $response
    ) : ResponseInterface {
        $response = $response->withStatus(Utils::getStatusCode($e, $response));

        if ($request->hasHeader('content-type') && \in_array('application/json', $request->getHeader('content-type'))) {
            return $this->prepareJsonErrorPlainResponse($e);
        }
        if ($this->renderer) {
            return $this->prepareTemplateResponse(
                $e,
                $this->renderer,
                [
                    'response' => $response,
                    'request'  => $request,
                    'uri'      => (string) $request->getUri(),
                    'status'   => $response->getStatusCode(),
                    'reason'   => $response->getReasonPhrase(),
                ],
                $response
            );
        }

        return $this->prepareErrorPlainResponse($response);
    }

    /**
     * @param Throwable $e
     * @param Renderer $renderer
     * @param array $templateData
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function prepareTemplateResponse(
        Throwable $e,
        Renderer $renderer,
        array $templateData,
        ResponseInterface $response
    ) : ResponseInterface {
        $response->getBody()
            ->write($renderer->render($this->template, $templateData));

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function prepareErrorPlainResponse(
        ResponseInterface $response
    ) : ResponseInterface {
        $message = "Oops, an 500 Internal Server Error occurred. ";
        $response->getBody()->write($message);
        return $response;
    }

    /**
     * @param Throwable $e
     * @return JsonResponse
     */
    private function prepareJsonErrorPlainResponse(Throwable $e) : JsonResponse
    {
        return new JsonResponse([
            'class' => \get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'message' => $e->getMessage(),
            'stackTrace' => $e->getTrace(),
        ]);
    }
}
