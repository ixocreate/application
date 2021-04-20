<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Console;

use Ixocreate\Application\ApplicationBootstrap;
use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Application\ApplicationInterface;
use Ixocreate\Application\Bootstrap\BootstrapFactory;

final class ConsoleApplication implements ApplicationInterface
{
    /**
     * @var string
     */
    private $bootstrapDirectory;

    /**
     * @var string
     */
    private $applicationCacheDirectory;

    private $isDevelopment = false;

    /**
     * ConsoleApplication constructor.
     *
     * @param string $bootstrapDirectory
     * @param string $applicationCacheDirectory
     */
    public function __construct(string $bootstrapDirectory, string $applicationCacheDirectory = 'resources/generated/application/')
    {
        $this->bootstrapDirectory = $bootstrapDirectory;
        $this->applicationCacheDirectory = $applicationCacheDirectory;

        //TODO make a proper short syntax check (for grouped input options)
        if (isset($_SERVER['argv']) &&
            \is_array($_SERVER['argv']) &&
            (\array_search('-d', $_SERVER['argv'], true) !== false || \array_search('--development', $_SERVER['argv'], true) !== false)
        ) {
            $this->isDevelopment = true;
        }
    }

    /**
     * @throws \Exception
     * @codeCoverageIgnore
     */
    public function run(): void
    {
        $serviceManager = (new ApplicationBootstrap())->bootstrap(
            $this->bootstrapDirectory,
            $this->applicationCacheDirectory,
            $this,
            new BootstrapFactory(),
            !$this->isDevelopment
        );
        $serviceManager->get(ConsoleRunner::class)->run();
    }

    public function configure(ApplicationConfigurator $applicationConfigurator): void
    {
        if ($this->isDevelopment === true) {
            $applicationConfigurator->setDevelopment(true);
        }
    }
}
