<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Application\Http;

use Ixocreate\Application\ApplicationBootstrap;
use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Application\ApplicationInterface;
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
     * @var string
     */
    private $applicationCacheDirectory;

    /**
     * HttpApplication constructor.
     *
     * @param string $bootstrapDirectory
     * @param string $applicationCacheDirectory
     */
    public function __construct(string $bootstrapDirectory, string $applicationCacheDirectory = 'resources/generated/application/')
    {
        $this->bootstrapDirectory = $bootstrapDirectory;
        $this->applicationCacheDirectory = $applicationCacheDirectory;
    }

    /**
     *
     */
    public function run(): void
    {
        /** @var ServiceManager $serviceManager */
        $serviceManager = (new ApplicationBootstrap())->bootstrap($this->bootstrapDirectory, $this->applicationCacheDirectory, $this);
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
