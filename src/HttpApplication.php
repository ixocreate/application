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
use KiwiSuite\Application\Bootstrap\ConfigBootstrap;
use KiwiSuite\Application\Bootstrap\MiddlewareBootstrap;
use KiwiSuite\Application\Bootstrap\PipeBootstrap;
use KiwiSuite\Application\Bootstrap\RouteBootstrap;
use KiwiSuite\Application\Bootstrap\ServiceManagerBootstrap;
use KiwiSuite\Application\Http\Pipe\PipeConfig;
use KiwiSuite\Application\Http\Route\RouteConfig;
use KiwiSuite\ServiceManager\Resolver\CacheResolver;
use KiwiSuite\ServiceManager\Resolver\InMemoryResolver;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerSetup;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Expressive\Application;
use Zend\Expressive\Emitter\EmitterStack;
use Zend\Expressive\Router\FastRouteRouter;

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
     * @var array
     */
    private $helpers = [];

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
        $applicationConfigurator->addBootstrapItem(ConfigBootstrap::class, 10);
        $applicationConfigurator->addBootstrapItem(ServiceManagerBootstrap::class, 20);
        $applicationConfigurator->addBootstrapItem(MiddlewareBootstrap::class, 100);
        $applicationConfigurator->addBootstrapItem(PipeBootstrap::class, 100);
        $applicationConfigurator->addBootstrapItem(RouteBootstrap::class, 100);
    }

    /**
     *
     */
    private function createServiceManager(): void
    {
        $serviceManagerConfig = $this->helpers[ServiceManagerConfig::class];

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
                $this->services = \array_merge($this->services, $bootstrapItemResult->getServices());
            }
            if ($bootstrapItemResult->hasHelpers()) {
                $this->helpers = \array_merge($this->helpers, $bootstrapItemResult->getHelpers());
            }
        }
    }

    /**
     *
     */
    private function createHttpStack(): void
    {
        $emitter = new EmitterStack();
        $emitter->push(new SapiEmitter());

        $app = new Application(
            new FastRouteRouter(),
            $this->serviceManager->get('MiddlewareSubManager'),
            null,
            $emitter
        );

        $this->addPipes($app);
        $this->addRoutes($app);

        $app->run();
    }

    private function addPipes(Application $app): void
    {
        /** @var PipeConfig $pipeConfig */
        $pipeConfig = $this->helpers[PipeConfig::class];
        foreach ($pipeConfig->getGlobalPipe() as $globalPipe) {
            $app->pipe($globalPipe);
        }

        $app->pipeRoutingMiddleware();
        foreach ($pipeConfig->getRoutingPipe() as $routingPipe) {
            $app->pipe($routingPipe);
        }

        $app->pipeDispatchMiddleware();
        foreach ($pipeConfig->getDispatchPipe() as $dispatchPipe) {
            $app->pipe($dispatchPipe);
        }
    }

    private function addRoutes(Application $app): void
    {
        /** @var RouteConfig $routeConfig */
        $routeConfig = $this->helpers[RouteConfig::class];

        foreach ($routeConfig->getRoutes() as $route) {
            $app->route(
                $route['path'],
                $route['middleware'],
                $route['methods'],
                $route['name']
            );
        }
    }
}
