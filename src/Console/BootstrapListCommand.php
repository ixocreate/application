<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Console\CommandInterface;;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class BootstrapListCommand extends Command implements CommandInterface
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    /**
     * BootstrapListCommand constructor.
     */
    public function __construct(ApplicationConfig $applicationConfig)
    {
        parent::__construct(self::getCommandName());
        $this->applicationConfig = $applicationConfig;
    }

    public function configure()
    {
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $data = [];
        foreach ($this->applicationConfig->getBootstrapItems() as $bootstrapItem) {
            $data[$bootstrapItem->getFileName()] = [
                $bootstrapItem->getFileName(),
                (\file_exists($this->applicationConfig->getBootstrapDirectory() . $bootstrapItem->getFileName())) ? '<info>Used</info>' : '<comment>Unused</comment>',
                (\file_exists($this->applicationConfig->getBootstrapDirectory() . $this->applicationConfig->getBootstrapEnvDirectory() . $bootstrapItem->getFileName())) ? '<info>Used</info>' : '<comment>Unused</comment>',
            ];
        }
        \sort($data);

        $io->table(
            ['File', 'Status', 'Env'],
            $data
        );
    }

    public static function getCommandName()
    {
        return "bootstrap:list";
    }
}
