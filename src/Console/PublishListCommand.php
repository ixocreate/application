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

use KiwiSuite\Application\Publish\PublishConfig;
use KiwiSuite\Application\Publish\PublishDefinitionConfig;
use KiwiSuite\Contract\Command\CommandInterface;
use KiwiSuite\Filesystem\Storage\StorageSubManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PublishListCommand extends Command implements CommandInterface
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

    public function __construct(PublishDefinitionConfig $publishDefinitionConfig, PublishConfig $publishConfig)
    {
        parent::__construct(self::getCommandName());

        $this->publishDefinitionConfig = $publishDefinitionConfig;
        $this->publishConfig = $publishConfig;
    }

    public static function getCommandName()
    {
        return 'publish:list';
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $definitions = $this->publishDefinitionConfig->getDefinitions($this->publishConfig);

        $data = [];

        foreach ($definitions as $name => $definition) {
            $data[] = [$name];
        }

        $io->table(
            ['Publish'],
            $data
        );
    }
}
