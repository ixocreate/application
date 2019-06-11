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
     * @return array|null
     */
    public function getBootstrapItems(): ?array;

    /**
     * @return string|null
     */
    public function getBootstrapDirectory(): ?string;

    /**
     * @return array|null
     */
    public function getDependencies(): ?array;
}
