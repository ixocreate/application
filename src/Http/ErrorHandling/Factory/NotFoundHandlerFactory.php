<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\ApplicationHttp\ErrorHandling\Factory;

use Ixocreate\ApplicationHttp\ErrorHandling\Response\NotFoundHandler;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\Package\Template\Renderer;
use Zend\Diactoros\Response;
use Ixocreate\Config\Config;

final class NotFoundHandlerFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return NotFoundHandler|mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get(Config::class)->get('error');

        $renderer = $container->has(Renderer::class)
            ? $container->get(Renderer::class)
            : null;

        $template = isset($config['template_404'])
            ? $config['template_404']
            : NotFoundHandler::TEMPLATE_DEFAULT;

        return new NotFoundHandler(
            function () {
                return new Response();
            },
            $renderer,
            $template
        );
    }
}
