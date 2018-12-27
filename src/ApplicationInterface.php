<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Application;

interface ApplicationInterface
{
    /**
     *
     */
    public function run(): void;

    /**
     * @param ApplicationConfigurator $applicationConfigurator
     */
    public function configure(ApplicationConfigurator $applicationConfigurator): void;
}
