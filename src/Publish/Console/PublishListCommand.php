<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Publish\Console;

use Ixocreate\Application\Console\CommandInterface;
use Ixocreate\Application\Publish\PublishConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PublishListCommand extends Command implements CommandInterface
{
    /**
     * @var PublishConfig
     */
    private $publishConfig;

    public function __construct(PublishConfig $publishConfig)
    {
        parent::__construct(self::getCommandName());

        $this->publishConfig = $publishConfig;
    }

    public static function getCommandName()
    {
        return 'publish:list';
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $definitions = $this->publishConfig->getDefinitions();

        $data = [];

        foreach ($definitions as $name => $definition) {
            $data[] = [$name];
        }

        $io->table(
            ['Publish'],
            $data
        );

        return 0;
    }
}
