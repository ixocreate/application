<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\ErrorHandling\Factory;

use Ixocreate\Application\Config\Config;
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
        $config = $container->get(Config::class)->get('error');

        $pageHandler = new PrettyPageHandler();
        $this->injectEditor($pageHandler, $config, $container);

        $whoops = new Whoops();
        $whoops->writeToOutput(false);
        $whoops->allowQuit(false);
        $whoops->pushHandler($pageHandler);
        $this->registerJsonHandler($whoops, $config);
        $whoops->register();
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
        if (empty($config['json_exceptions']['display'])) {
            return;
        }

        $handler = new JsonResponseHandler();

        if (! empty($config['json_exceptions']['show_trace'])) {
            $handler->addTraceToOutput(true);
        }

        if (! empty($config['json_exceptions']['ajax_only'])) {
            if (\method_exists(WhoopsUtil::class, 'isAjaxRequest')) {
                // Whoops 2.x; don't push handler on stack unless we are in
                // an XHR request.
                if (! WhoopsUtil::isAjaxRequest()) {
                    return;
                }
            } elseif (\method_exists($handler, 'onlyForAjaxRequests')) {
                // Whoops 1.x
                $handler->onlyForAjaxRequests(true);
            }
        }

        $whoops->pushHandler($handler);
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
