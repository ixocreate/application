<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Contract\Application\BootstrapItemInterface;
use Ixocreate\Contract\Command\CommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;

final class BootstrapGenerateCommand extends Command implements CommandInterface
{
    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    private $template = <<<'EOD'
<?php
declare(strict_types=1);

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
            ->addOption('env', 'e', InputOption::VALUE_NONE, 'save bootstrap file in env directory')
            ->setAliases(['bootstrap:generate']);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $this->applicationConfig->getBootstrapDirectory();
        if ($input->getOption('env') === true) {
            $directory .= $this->applicationConfig->getBootstrapEnvDirectory();
        }

        if (\file_exists($directory . $input->getArgument("file"))) {
            throw new \Exception("Bootstrap file already exists");
        }

        foreach ($this->applicationConfig->getBootstrapItems() as $bootstrapItem) {
            if ($bootstrapItem->getFileName() === $input->getArgument("file")) {
                $this->generateFile($directory, $bootstrapItem);
                $output->writeln(\sprintf("<info>%s generated</info>", $bootstrapItem->getFileName()));
                return;
            }
        }

        throw new \Exception(\sprintf("Bootstrap file %s does not exist", $input->getArgument("file")));
    }

    public static function getCommandName()
    {
        return "make:bootstrap";
    }

    /**
     * @param string $directory
     * @param BootstrapItemInterface $bootstrapItem
     */
    private function generateFile(string $directory, BootstrapItemInterface $bootstrapItem): void
    {
        \file_put_contents(
            $directory . $bootstrapItem->getFileName(),
            \sprintf($this->template, '\\' . \get_class($bootstrapItem->getConfigurator()), '$' . $bootstrapItem->getVariableName())
        );
    }
}
