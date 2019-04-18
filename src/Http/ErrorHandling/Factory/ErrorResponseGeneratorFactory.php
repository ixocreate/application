<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\ErrorHandling\Factory;

use Ixocreate\Application\Http\ErrorHandling\Response\ErrorResponseGenerator;
use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Config\Config;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\Template\Package\Renderer;
use Zend\Expressive\Middleware\WhoopsErrorResponseGenerator;

final class ErrorResponseGeneratorFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return ErrorResponseGenerator|mixed|WhoopsErrorResponseGenerator
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $develop = $container->get(ApplicationConfig::class)->isDevelopment();

        $config = $container->get(Config::class)->get('error');

        $renderer = $container->has(Renderer::class)
            ? $container->get(Renderer::class)
            : null;

        if ($develop === true) {
            return new WhoopsErrorResponseGenerator((new WhoopsFactory())($container, $requestedName, $options));
        }
        $template = isset($config['template_error'])
            ? $config['template_error']
            : ErrorResponseGenerator::TEMPLATE_DEFAULT;

        return new ErrorResponseGenerator($renderer, $template);
    }
}
