<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application\ServiceManager;

use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

class DateTimeFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return \DateTime|mixed
     * @throws \Exception
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        return new \DateTime();
    }
}
