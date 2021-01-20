<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\ErrorHandling\Factory;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Http\ErrorHandling\Response\ErrorResponseGenerator;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\Template\Renderer;
use Mezzio\Middleware\WhoopsErrorResponseGenerator;

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
        /** @var ApplicationConfig $config */
        $config = $container->get(ApplicationConfig::class);

        if ($config->isErrorDisplay()) {
            return new WhoopsErrorResponseGenerator((new WhoopsFactory())($container, $requestedName, $options));
        }

        $renderer = $container->has(Renderer::class) ? $container->get(Renderer::class) : null;

        $template = $config->errorTemplate() ?? ErrorResponseGenerator::TEMPLATE_DEFAULT;

        return new ErrorResponseGenerator($renderer, $template);
    }
}
