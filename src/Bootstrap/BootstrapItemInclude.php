<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Bootstrap;

final class BootstrapItemInclude
{
    /**
     * @param string $filename
     * @param array $args
     */
    public static function include(string $filename, array $args = [])
    {
        if (!empty($args)) {
            \extract($args);
        }

        unset($args);

        require $filename;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function normalizePath(string $path): string
    {
        if (empty($path)) {
            return './';
        }

        return \rtrim($path, '/') . '/';
    }
}
