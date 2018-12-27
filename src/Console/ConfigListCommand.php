<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Contract\Application\ConfigExampleInterface;
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
     * BootstrapListCommand constructor.
     */
    public function __construct(ApplicationConfig $applicationConfig)
    {
        parent::__construct(self::getCommandName());
        $this->applicationConfig = $applicationConfig;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $data = [];
        foreach ($this->applicationConfig->getPackages() as $package) {
            if (!empty($package->getConfigProvider())) {
                foreach ($package->getConfigProvider() as $provider) {
                    if (!\is_subclass_of($provider, ConfigExampleInterface::class)) {
                        continue;
                    }

                    $provider = new $provider();

                    $data[] = [
                        $provider->configName(),
                        (\file_exists($this->applicationConfig->getConfigDirectory() . 'local/' . $provider->configName() . '.config.php')) ? '<info>Used</info>' : '<comment>Unused</comment>',
                    ];
                }
            }
        }

        $io->table(
            ['File', 'Status'],
            $data
        );
    }

    public static function getCommandName()
    {
        return "config:list";
    }
}
