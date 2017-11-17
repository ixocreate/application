<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Application;

use KiwiSuite\Application\Bootstrap\BootstrapItemResult;
use KiwiSuite\Application\Bootstrap\ServiceManagerBootstrap;
use KiwiSuite\ServiceManager\Resolver\CacheResolver;
use KiwiSuite\ServiceManager\Resolver\InMemoryResolver;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerSetup;

final class HttpApplication implements ApplicationInterface
{
    /**
     * @var string
     */
    private $bootstrapDirectory;

    /**
     * @var ApplicationConfig
     */
    private $applicationConfig;

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    /**
     * @var array
     */
    private $services = [];

    /**
     * HttpApplication constructor.
     * @param string $bootstrapDirectory
     */
    public function __construct(string $bootstrapDirectory)
    {
        if (empty($bootstrapDirectory)) {
            $bootstrapDirectory = ".";
        }
        $this->bootstrapDirectory = \rtrim($bootstrapDirectory, '/') . '/';
    }

    /**
     *
     */
    public function run(): void
    {
        $this->createApplicationConfig();
        $this->bootstrap();
        $this->createServiceManager();
        $this->createHttpStack();
    }

    /**
     *
     */
    private function createApplicationConfig(): void
    {
        $applicationConfigurator = new ApplicationConfigurator($this->bootstrapDirectory);
        $this->configureDefaultBootstrap($applicationConfigurator);

        if (\file_exists($this->bootstrapDirectory . 'application.php')) {
            IncludeHelper::include(
                $this->bootstrapDirectory . 'application.php',
                ['applicationConfigurator' => $applicationConfigurator]
            );
        }

        $this->applicationConfig = $applicationConfigurator->getApplicationConfig();
    }

    private function configureDefaultBootstrap(ApplicationConfigurator $applicationConfigurator)
    {
        $applicationConfigurator->addBootstrapItem(ServiceManagerBootstrap::class, 10);
    }

    /**
     *
     */
    private function createServiceManager(): void
    {
        $serviceManagerConfig = $this->services[ServiceManagerConfig::class];
        unset($this->services[ServiceManagerConfig::class]);

        $this->serviceManager = new ServiceManager(
            $serviceManagerConfig,
            new ServiceManagerSetup([
                'autowireResolver'      => ($this->applicationConfig->isDevelopment()) ? InMemoryResolver::class: CacheResolver::class,
                'persistRoot'           => $this->applicationConfig->getPersistCacheDirectory() . 'servicemanager/',
                'persistLazyLoading'    => !$this->applicationConfig->isDevelopment(),
            ]),
            $this->services
        );
    }

    /**
     *
     */
    private function bootstrap(): void
    {
        foreach ($this->applicationConfig->getBootstrapQueue() as $bootstrapItem) {
            /** @var BootstrapItemResult $bootstrapItemResult */
            $bootstrapItemResult = (new $bootstrapItem())->bootstrap($this->applicationConfig);
            if ($bootstrapItemResult->hasServices()) {
                $this->services = \array_merge($bootstrapItemResult->getServices());
            }
        }
    }

    /**
     *
     */
    private function createHttpStack(): void
    {
        echo "test";
    }
}
