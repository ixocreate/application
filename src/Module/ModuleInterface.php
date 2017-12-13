<?php

namespace KiwiSuite\Application\Module;


interface ModuleInterface
{
    public function getConfigDirectory(): string;

    public function getBootstrapDirectory(): string;
}