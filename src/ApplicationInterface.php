<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application;

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
