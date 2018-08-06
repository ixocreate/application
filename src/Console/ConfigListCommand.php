<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Application\Console;

use KiwiSuite\Application\ApplicationConfig;
use KiwiSuite\Contract\Application\ConfigExampleInterface;
use KiwiSuite\Contract\Command\CommandInterface;
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
                    if (!is_subclass_of($provider, ConfigExampleInterface::class)) {
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
