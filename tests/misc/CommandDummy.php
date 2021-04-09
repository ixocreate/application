<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Misc\Application;

use Ixocreate\Application\Console\CommandInterface;
use Symfony\Component\Console\Command\Command;

class CommandDummy extends Command implements CommandInterface
{
    public static function getCommandName()
    {
        return 'dummy-command';
    }
}
