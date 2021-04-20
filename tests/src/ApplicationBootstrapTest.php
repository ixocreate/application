<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application;

use Ixocreate\Application\ApplicationBootstrap;
use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Application\ApplicationInterface;
use Ixocreate\Application\Bootstrap\BootstrapFactory;
use Ixocreate\Application\Bootstrap\BootstrapFactoryInterface;
use Ixocreate\Application\Package\BootInterface;
use Ixocreate\Application\Service\ServiceHandlerInterface;
use Ixocreate\Application\Service\ServiceRegistry;
use Ixocreate\Application\ServiceManager\ServiceManagerConfig;
use Ixocreate\ServiceManager\ServiceManager;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Application\ApplicationBootstrap
 */
class ApplicationBootstrapTest extends TestCase
{
    private $vfsCache;

    protected function tearDown(): void
    {
    }

    protected function setUp(): void
    {
        $this->vfsCache = vfsStream::setup('data');
    }

    public function testMinimalBootstrap()
    {
        $application = $this->createMock(ApplicationInterface::class);

        \mkdir($this->vfsCache->url() . '/bootstrap');
        \mkdir($this->vfsCache->url() . '/cache');

        $bootstrap = new ApplicationBootstrap();
        $serviceManager = $bootstrap->bootstrap($this->vfsCache->url() . '/bootstrap', $this->vfsCache->url() . '/cache/', $application, new BootstrapFactory());

        $this->assertTrue($this->vfsCache->getChild('cache')->hasChildren());

        $this->assertInstanceOf(ServiceManager::class, $serviceManager);
        $this->assertInstanceOf(ApplicationConfig::class, $serviceManager->get(ApplicationConfig::class));
    }

    /**
     * runInSeparateProcess
     */
    public function testPackageBoot()
    {
        $vfsBootstrap = vfsStream::setup();
        $configFile = vfsStream::newFile('application.php')->at($vfsBootstrap);
        $configFile->write(
            <<<'EOF'
<?php

declare(strict_types=1);

use Ixocreate\Application\ApplicationConfigurator;

/** @var ApplicationConfigurator $application */
EOF
        );
        $serviceRegistry = new ServiceRegistry();
        $application = $this->createMock(ApplicationInterface::class);

        $bootPackageMock = $this->createMock(BootInterface::class);
        $bootPackageMock
            ->expects($this->once())
            ->method('boot');

        $appConfig = $this->createMock(ApplicationConfig::class);
        $appConfig
            ->method('isDevelopment')
            ->willReturn(true);
        $appConfig
            ->method('getPersistCacheDirectory')
            ->willReturn('persistCache');
        $appConfig
            ->method('getBootPackages')
            ->willReturn([$bootPackageMock]);

        $applicationConfigurator = $this->createMock(ApplicationConfigurator::class);
        $applicationConfigurator
            ->method('getBootstrapDirectory')
            ->willReturn($vfsBootstrap->url() . '/');
        $applicationConfigurator
            ->method('getBootstrapEnvDirectory')
            ->willReturn('local');
        $applicationConfigurator
            ->method('getApplicationConfig')
            ->willReturn($appConfig);

        $serviceHandlerMock = $this->createMock(ServiceHandlerInterface::class);
        $serviceHandlerMock
            ->expects($this->once())
            ->method('load')
            ->willReturn($serviceRegistry);

        $bootstrapFactoryMock = $this->createMock(BootstrapFactoryInterface::class);
        $bootstrapFactoryMock
            ->expects($this->once())
            ->method('createApplicationConfigurator')
            ->willReturn($applicationConfigurator);
        $bootstrapFactoryMock
            ->expects($this->once())
            ->method('createServiceHandler')
            ->willReturn($serviceHandlerMock);

        $serviceConfigMock = $this->createMock(ServiceManagerConfig::class);
        $serviceRegistry->add(ServiceManagerConfig::class, $serviceConfigMock);


        $bootstrap = new ApplicationBootstrap();
        $serviceManager = $bootstrap->bootstrap(
            $vfsBootstrap->url() . '/',
            $vfsBootstrap->url() . '/',
            $application,
            $bootstrapFactoryMock
        );
    }

//
//    public function testCacheWrite()
//    {
//
//    }
}
