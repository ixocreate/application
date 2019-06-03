<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application\Scan;

use Ixocreate\Application\ServiceManager\NamedServiceInterface;

class Class1 implements NamedServiceInterface
{
    public static function serviceName(): string
    {
        return 'class1';
    }
}
