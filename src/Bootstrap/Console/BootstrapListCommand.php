<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Bootstrap\Console;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Console\CommandInterface;
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
        $envDirectories = \glob($this->applicationConfig->getBootstrapDirectory() . '*', GLOB_ONLYDIR);

        $data = [];
        foreach ($this->applicationConfig->getBootstrapItems() as $bootstrapItem) {
            $data[$bootstrapItem->getFileName()] = [
                $bootstrapItem->getFileName(),
                (\file_exists($this->applicationConfig->getBootstrapDirectory() . $bootstrapItem->getFileName())) ? '<info>Used</info>' : '<comment>Unused</comment>',
            ];

            foreach ($envDirectories as $directory) {
                $data[$bootstrapItem->getFileName()][] = (\file_exists($directory . '/' . $bootstrapItem->getFileName())) ? '<info>Used</info>' : '<comment>Unused</comment>';
            }
        }
        \sort($data);

        $headers = ['File', 'Global'];
        foreach ($envDirectories as $directory) {
            $headers[] = \str_replace($this->applicationConfig->getBootstrapDirectory(), '', $directory);
        }

        $io = new SymfonyStyle($input, $output);
        $io->table(
            $headers,
            $data
        );
    }

    public static function getCommandName()
    {
        return 'bootstrap:list';
    }
}
