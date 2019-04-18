<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http;

use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Application\ApplicationInterface;
use Ixocreate\Application\Bootstrap\Bootstrap;
use Ixocreate\Application\Http\Pipe\PipeConfig;
use Ixocreate\ServiceManager\ServiceManager;
use Zend\HttpHandlerRunner\RequestHandlerRunner;

final class HttpApplication implements ApplicationInterface
{
    /**
     * @var string
     */
    private $bootstrapDirectory;

    /**
     * ConsoleApplication constructor.
     * @param string $bootstrapDirectory
     */
    public function __construct(string $bootstrapDirectory)
    {
        $this->bootstrapDirectory = $bootstrapDirectory;
    }

    /**
     *
     */
    public function run(): void
    {
        /** @var ServiceManager $serviceManager */
        $serviceManager = (new Bootstrap())->bootstrap($this->bootstrapDirectory, $this);
        ($serviceManager->build(RequestHandlerRunner::class, [
            PipeConfig::class => $serviceManager->get(PipeConfig::class),
        ]))->run();
    }

    /**
     * @param ApplicationConfigurator $applicationConfigurator
     */
    public function configure(ApplicationConfigurator $applicationConfigurator): void
    {
    }
}
