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

namespace Ixocreate\Application\Console;

use Ixocreate\Application\Publish\PublishConfig;
use Ixocreate\Application\Publish\PublishDefinitionConfig;
use Ixocreate\Contract\Command\CommandInterface;
use Ixocreate\Filesystem\Storage\StorageSubManager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\MountManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class PublishCommand extends Command implements CommandInterface
{
    /**
     * @var PublishDefinitionConfig
     */
    private $publishDefinitionConfig;

    /**
     * @var PublishConfig
     */
    private $publishConfig;

    /**
     * @var StorageSubManager
     */
    private $storageSubManager;

    public function __construct(PublishDefinitionConfig $publishDefinitionConfig, PublishConfig $publishConfig, StorageSubManager $storageSubManager)
    {
        parent::__construct(self::getCommandName());

        $this->publishDefinitionConfig = $publishDefinitionConfig;
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
            ->addOption("force", "f", InputOption::VALUE_NONE, 'Force overwrite');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $definitions = $this->publishDefinitionConfig->getDefinitions($this->publishConfig);

        if (!empty($input->getArgument("type"))) {
            foreach ($definitions as $name => $definition) {
                if ($name === $input->getArgument("type")) {
                    $definitions[$name] = $definition;
                    break;
                }
            }
        }

        $rootStorage = new Filesystem(new Local(\getcwd(), LOCK_EX, Local::SKIP_LINKS));

        foreach ($definitions as $name => $definition) {
            /** @var FilesystemInterface $storage */
            $storage = $this->storageSubManager->get($definition['storage']);

            $mountManager = new MountManager([
                'root' => $rootStorage,
                'storage' => $storage,
            ]);

            foreach ($definition['sources'] as $directory) {
                foreach ($mountManager->listContents('root://' . $directory, true) as $content) {
                    if ($content['type'] !== "file") {
                        continue;
                    }

                    $sourceFile = \str_replace($directory . "/", "", $content['path']);

                    if ((bool) $input->getOption("force") === false && $mountManager->has('storage://' . $sourceFile)) {
                        continue;
                    }

                    try {
                        $deleted = $mountManager->delete('storage://' . $sourceFile);
                    } catch (FileNotFoundException $e) {
                        $deleted = true;
                    }

                    if ($deleted) {
                        $mountManager->copy('root://' . $content['path'], 'storage://' . $sourceFile);
                    }
                }
            }

            $output->writeln(\sprintf("%s published", $name));
        }
    }
}
