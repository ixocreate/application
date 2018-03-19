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
use KiwiSuite\ApplicationConsole\Command\CommandInterface;
use KiwiSuite\Contract\Application\BootstrapItemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BootstrapGenerateCommand extends Command implements CommandInterface
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    private $template = <<<'EOD'
<?php
namespace App;
/** @var %s %s */

EOD;

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
            ->addArgument('file', InputArgument::REQUIRED, 'Bootstrap file name')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (\file_exists($this->applicationConfig->getBootstrapDirectory() . $input->getArgument("file"))) {
            throw new \Exception("Bootstrap file already exists");
        }

        foreach ($this->applicationConfig->getBootstrapItems() as $bootstrapItem) {
            if ($bootstrapItem->getFileName() === $input->getArgument("file")) {
                $this->generateFile($bootstrapItem);
                $output->writeln(\sprintf("<info>%s generated</info>", $bootstrapItem->getFileName()));
                return;
            }
        }

        throw new \Exception(\sprintf("Bootstrap file %s does not exist", $input->getArgument("file")));
    }

    public static function getCommandName()
    {
        return "bootstrap:generate";
    }

    private function generateFile(BootstrapItemInterface $bootstrapItem): void
    {
        \file_put_contents(
            $this->applicationConfig->getBootstrapDirectory() . $bootstrapItem->getFileName(),
            \sprintf($this->template, '\\' . \get_class($bootstrapItem->getConfigurator()), '$' . $bootstrapItem->getVariableName())
        );
    }
}
