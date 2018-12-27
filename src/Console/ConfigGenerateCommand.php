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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ConfigGenerateCommand extends Command implements CommandInterface
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
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Config file name');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->applicationConfig->getPackages() as $package) {
            if (!empty($package->getConfigProvider())) {
                foreach ($package->getConfigProvider() as $provider) {
                    if (!\is_subclass_of($provider, ConfigExampleInterface::class)) {
                        continue;
                    }
                    /** @var ConfigExampleInterface $provider */
                    $provider = new $provider();
                    \file_put_contents(
                        $this->applicationConfig->getConfigDirectory() . 'local/' . $provider->configName() . '.config.php',
                        $provider->configContent()
                    );

                    $output->writeln(\sprintf("<info>%s generated</info>", $provider->configName()));
                    return;
                }
            }
        }

        throw new \Exception(\sprintf("Config file %s does not exist", $input->getArgument("file")));
    }

    public static function getCommandName()
    {
        return "config:generate";
    }
}
