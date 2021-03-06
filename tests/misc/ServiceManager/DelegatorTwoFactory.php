<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application\ServiceManager;

use Ixocreate\ServiceManager\DelegatorFactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

class DelegatorTwoFactory implements DelegatorFactoryInterface
{
    public function __invoke(ServiceManagerInterface $container, $name, callable $callback, array $options = null)
    {
        return new \DateTime();
    }
}
