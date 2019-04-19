<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console\Factory;

use Ixocreate\Application\Console\CommandMapping;
use Ixocreate\Application\Console\ConsoleSubManager;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\ServiceManager\Exception\ServiceNotCreatedException;

class CommandMapFactory implements FactoryInterface
{
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $commandMap = $container->get(CommandMapping::class)->getMapping();

        if (!\array_key_exists($requestedName, $commandMap)) {
            throw new ServiceNotCreatedException(\sprintf('Unable to create command %s. Name not found in CommandMap', $requestedName));
        }

        return $container->get(ConsoleSubManager::class)->get($commandMap[$requestedName]);
    }
}
