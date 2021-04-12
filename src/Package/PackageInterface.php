<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Package;

interface PackageInterface
{
    /**
     * @return array
     */
    public function getBootstrapItems(): array;

    /**
     * @return string|null
     */
    public function getBootstrapDirectory(): ?string;

    /**
     * @return array
     */
    public function getDependencies(): array;
}
