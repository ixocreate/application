<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Application\Config;

final class Config
{
    /**
     * @var array
     */
    private $config;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->config;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        $config = $this->config;

        foreach (\explode(".", $key) as $segment) {
            if (\array_key_exists($segment, $config)) {
                $config = $config[$segment];
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * @param string $key
     * @param null $default
     * @return array|mixed|null
     */
    public function get(string $key, $default = null)
    {
        if (!$this->has($key)) {
            return $default;
        }

        $config = $this->config;

        foreach (\explode(".", $key) as $segment) {
            if (\array_key_exists($segment, $config)) {
                $config = $config[$segment];
                continue;
            }
        }

        return $config;
    }
}
