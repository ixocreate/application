<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console;

use Ixocreate\Application\Console\Factory\CommandMapFactory;
use Ixocreate\ServiceManager\SubManager\SubManager;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

final class ConsoleSubManager extends SubManager implements CommandLoaderInterface
{
    /**
     * @return array|string[]
     */
    public function getNames(): array
    {
        $names = [];
        foreach ($this->getServiceManagerConfig()->getFactories() as $name => $factory) {
            if ($factory === CommandMapFactory::class) {
                $names[] = $name;
            }
        }
        return $names;
    }
}
