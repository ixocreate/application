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
namespace KiwiSuite\Application\Bootstrap;

final class BootstrapItemResult
{
    /**
     * @var array
     */
    private $services = [];

    /**
     * BootstrapItemResult constructor.
     * @param array $services
     */
    public function __construct(array $services = [])
    {
        $this->services = $services;
    }

    /**
     * @return bool
     */
    public function hasServices(): bool
    {
        return \count($this->services) > 0;
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }
}
