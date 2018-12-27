<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console;

use Ixocreate\Application\Publish\PublishConfig;
use Ixocreate\Application\Publish\PublishDefinitionConfig;
use Ixocreate\Contract\Command\CommandInterface;
use Ixocreate\Filesystem\Storage\StorageSubManager;
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
