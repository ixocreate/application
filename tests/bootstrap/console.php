<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

use Ixocreate\Application\ServiceManager\ServiceManagerConfigurator;
use Symfony\Component\Console\Command\HelpCommand;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(HelpCommand::class);
