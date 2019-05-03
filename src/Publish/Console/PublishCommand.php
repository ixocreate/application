<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Publish\Console;

use FilesystemIterator;
use Ixocreate\Application\Console\CommandInterface;
use Ixocreate\Application\Publish\PublishConfig;
use Ixocreate\Filesystem\Storage\StorageSubManager;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class PublishCommand extends Command implements CommandInterface
{
    /**
     * @var PublishConfig
     */
    private $publishConfig;

    /**
     * @var StorageSubManager
     */
    private $storageSubManager;

    public function __construct(PublishConfig $publishConfig, StorageSubManager $storageSubManager)
    {
        parent::__construct(self::getCommandName());

        $this->publishConfig = $publishConfig;
        $this->storageSubManager = $storageSubManager;
    }

    public static function getCommandName()
    {
        return 'publish:run';
    }

    public function configure()
    {
        $this
            ->addArgument('type', InputArgument::OPTIONAL, 'Publish a given type of publishable files')
            ->addOption("force", "f", InputOption::VALUE_NONE, 'Force overwrite')
            ->setAliases(['publish']);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $definitions = $this->publishConfig->getDefinitions();

        if (!empty($input->getArgument('type'))) {
            foreach ($definitions as $name => $definition) {
                if ($name === $input->getArgument('type')) {
                    $definitions[$name] = $definition;
                    break;
                }
            }
        }

        foreach ($definitions as $name => $definition) {
            if (!\is_dir($definition['targetDirectory'])) {
                throw new \Exception('target is not a directory: ' . $definition['targetDirectory']);
            }
            if (!\is_writable($definition['targetDirectory'])) {
                throw new \Exception('target is not writable: ' . $definition['targetDirectory']);
            }

            $targetPermissions = \fileperms($definition['targetDirectory']);

            foreach ($definition['sources'] as $directory) {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::SELF_FIRST
                );

                foreach ($iterator as $file) {
                    /** @var \SplFileInfo $file */
                    $path = $file->getPathname();

                    if (\preg_match('#(^|/|\\\\)\.{1,2}$#', $path)) {
                        continue;
                    }

                    if (!$file->isFile()) {
                        continue;
                    }

                    $sourceFile = \str_replace($directory . '/', '', $file->getPathname());
                    $targetFile = $definition['targetDirectory'] . $sourceFile;

                    if (\file_exists($targetFile)) {
                        if ((bool) $input->getOption('force') === false) {
                            continue;
                        }
                        \unlink($targetFile);
                    }

                    $targetDirectory = \dirname($targetFile);

                    if (!\is_dir($targetDirectory)) {
                        if (!@\mkdir($targetDirectory, $targetPermissions, true)) {
                            throw new \Exception(\error_get_last());
                        }
                    }

                    \copy($path, $targetFile);

                    continue;
                }
            }

            $output->writeln(\sprintf('%s published', $name));
        }
    }
}
