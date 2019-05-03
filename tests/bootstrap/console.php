<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Symfony\Component\Console\Command\HelpCommand;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(HelpCommand::class);
