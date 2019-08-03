<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Exception;

use Psr\Container\NotFoundExceptionInterface;

class ConfiguratorNotFoundException extends \InvalidArgumentException implements NotFoundExceptionInterface
{
}
