<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console\Console\Factory;

use Ixocreate\Application\Console\Console\ConsoleRunner;
use Ixocreate\Application\Console\ConsoleSubManager;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

final class ConsoleRunnerFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return ConsoleRunner
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        /** @var ConsoleSubManager $consoleSubManager */
        $consoleSubManager = $container->get(ConsoleSubManager::class);

        $application = new ConsoleRunner('ixocreate', '0.1');
        $application->setCommandLoader($consoleSubManager);

        return $application;
    }
}
