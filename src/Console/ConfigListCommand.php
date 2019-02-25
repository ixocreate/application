<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Contract\Application\ConfiguratorInterface;
use Ixocreate\Contract\Command\CommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ConfigListCommand extends Command implements CommandInterface
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    /**
     * ConfigListCommand constructor.
     * @param ApplicationConfig $applicationConfig
     */
    public function __construct(ApplicationConfig $applicationConfig)
    {
        parent::__construct(self::getCommandName());
        $this->applicationConfig = $applicationConfig;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $data = [];
        foreach ($this->applicationConfig->getPackages() as $package) {
            if (!empty($package->getConfigProvider())) {
                foreach ($package->getConfigProvider() as $provider) {
                    /** @var ConfiguratorInterface $provider */
                    $provider = new $provider();

                    $data[] = [
                        $provider->configName(),
                        (\file_exists($this->applicationConfig->getConfigDirectory() . $this->applicationConfig->getConfigEnvDirectory() . $provider->configName() . '.config.php')) ? '<info>Used</info>' : '<comment>Unused</comment>',
                    ];
                }
            }
        }

        $io->table(
            ['File', 'Status'],
            $data
        );
    }

    /**
     * @return string
     */
    public static function getCommandName()
    {
        return "config:list";
    }
}
