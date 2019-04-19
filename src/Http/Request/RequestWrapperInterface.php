<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http\Request;

use Psr\Http\Message\ServerRequestInterface;

interface RequestWrapperInterface
{
    /**
     * @return ServerRequestInterface
     */
    public function previousRequest(): ServerRequestInterface;

    /**
     * @return ServerRequestInterface
     */
    public function originalRequest(): ServerRequestInterface;

    /**
     * @return ServerRequestInterface
     */
    public function rootRequest(): ServerRequestInterface;
}
