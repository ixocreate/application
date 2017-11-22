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
namespace KiwiSuite\Application\Http\Route;

final class RouteConfig
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * RouteConfig constructor.
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        //TODO Checks
        $this->routes = $routes;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
