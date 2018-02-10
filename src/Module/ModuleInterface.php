<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Application\Module;

use KiwiSuite\Application\Bootstrap\BootstrapInterface;

interface ModuleInterface extends BootstrapInterface
{
    /**
     * @return null|string
     */
    public function getBootstrapDirectory(): ?string;

    /**
     * @return null|string
     */
    public function getConfigDirectory(): ?string;

    /**
     * @return array|null
     */
    public function getBootstrapItems(): ?array;
}
