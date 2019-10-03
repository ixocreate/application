<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\ErrorHandling\Factory;

use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;
use Whoops\Util\Misc as WhoopsUtil;
use Zend\Expressive\Container\Exception;

final class WhoopsFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return mixed|Whoops
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $pageHandler = new PrettyPageHandler();
        //$this->injectEditor($pageHandler, $config, $container);

        foreach ($_ENV as $name => $value) {
            $pageHandler->blacklist('_ENV', $name);
            $pageHandler->blacklist('_SERVER', $name);
        }

        $whoops = new Whoops();
        $whoops->writeToOutput(false);
        $whoops->allowQuit(false);


        $whoops->appendHandler($pageHandler);
        //$this->registerJsonHandler($whoops, []);

        //$whoops->register();
        return $whoops;
    }

    /**
     * If configuration indicates a JsonResponseHandler, configure and register it.
     *
     * @param Whoops $whoops
     * @param array|\ArrayAccess $config
     * @return void
     */
    private function registerJsonHandler(Whoops $whoops, $config) : void
    {
        $handler = new JsonResponseHandler();

        $handler->addTraceToOutput(true);

        if (!WhoopsUtil::isAjaxRequest()) {
            return;
        }

        $whoops->appendHandler($handler);
    }

    /**
     * @param PrettyPageHandler $handler
     * @param $config
     * @param ServiceManagerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function injectEditor(PrettyPageHandler $handler, $config, ServiceManagerInterface $container) : void
    {
        if (! isset($config['editor'])) {
            return;
        }

        $editor = $config['editor'];

        if (\is_callable($editor)) {
            $handler->setEditor($editor);
            return;
        }

        if (! \is_string($editor)) {
            throw new Exception\InvalidServiceException(\sprintf(
                'Whoops editor must be a string editor name, string service name, or callable; received "%s"',
                \is_object($editor) ? \get_class($editor) : \gettype($editor)
            ));
        }

        if ($container->has($editor)) {
            $editor = $container->get($editor);
        }

        $handler->setEditor($editor);
    }
}
