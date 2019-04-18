<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console;

use Ixocreate\Application\Console\Factory\CommandInitializer;
use Ixocreate\Application\Console\Factory\CommandMapFactory;
use Ixocreate\Application\Configurator\ConfiguratorInterface;
use Ixocreate\Application\Exception\InvalidArgumentException;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\ServiceManager\Factory\AutowireFactory;
use Ixocreate\Application\Service\SubManagerConfigurator;

final class ConsoleConfigurator implements ConfiguratorInterface
{
    /**
     * @var SubManagerConfigurator
     */
    private $subManagerConfigurator;

    /**
     * MiddlewareConfigurator constructor.
     */
    public function __construct()
    {
        $this->subManagerConfigurator = new SubManagerConfigurator(ConsoleSubManager::class, CommandInterface::class);
        $this->subManagerConfigurator->addInitializer(CommandInitializer::class);
    }

    /**
     * @param string $directory
     * @param bool $recursive
     */
    public function addDirectory(string $directory, bool $recursive = true): void
    {
        $this->subManagerConfigurator->addDirectory($directory, $recursive);
    }

    /**
     * @param string $action
     * @param string $factory
     */
    public function addCommand(string $action, string $factory = AutowireFactory::class): void
    {
        $this->subManagerConfigurator->addFactory($action, $factory);
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $factories = $this->subManagerConfigurator->getServiceManagerConfig()->getFactories();

        $commandMap = [];
        foreach ($factories as $id => $factory) {
            if (!\is_subclass_of($id, CommandInterface::class, true)) {
                throw new InvalidArgumentException(\sprintf("'%s' doesn't implement '%s'", $id, CommandInterface::class));
            }
            $commandName = \forward_static_call([$id, 'getCommandName']);
            $commandMap[$commandName] = $id;

            $this->addCommand($commandName, CommandMapFactory::class);
        }

        $serviceRegistry->add(CommandMapping::class, new CommandMapping($commandMap));
        $this->subManagerConfigurator->registerService($serviceRegistry);
    }
}
