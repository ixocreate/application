<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Bootstrap\Console;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Console\CommandInterface;
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
            ->addArgument('env', InputArgument::OPTIONAL, 'save bootstrap file in env directory')
            ->setAliases(['make:bootstrap']);

        $this->setDescription('Create bootstrap file');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $this->applicationConfig->getBootstrapDirectory();
        if ($input->getArgument('env') !== null) {
            $directory .= \trim($input->getArgument('env'), " \n\r\t/\\") . '/';
        }

        if (\file_exists($directory . $input->getArgument('file'))) {
            throw new \Exception('Bootstrap file already exists');
        }

        foreach ($this->applicationConfig->getBootstrapItems() as $bootstrapItem) {
            if ($bootstrapItem->getFileName() === $input->getArgument('file')) {
                $this->generateFile($directory, $bootstrapItem);
                $output->writeln(\sprintf("<info>%s generated</info>", $bootstrapItem->getFileName()));
                return 0;
            }
        }

        throw new \Exception(\sprintf("Bootstrap file %s does not exist", $input->getArgument('file')));
    }

    public static function getCommandName()
    {
        return 'bootstrap:generate';
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
