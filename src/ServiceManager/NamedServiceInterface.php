<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\ServiceManager;

interface NamedServiceInterface extends \Ixocreate\ServiceManager\NamedServiceInterface
{
    public static function serviceName(): string;
}
