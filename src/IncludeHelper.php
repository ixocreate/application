<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Application;

final class IncludeHelper
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
            $path = ".";
        }

        return \rtrim($path, '/') . '/';
    }
}
