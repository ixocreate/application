<?php

namespace KiwiSuite\Application;

use KiwiSuite\Application\Bootstrap\BootstrapRegistry;
use KiwiSuite\Application\Module\ModuleInterface;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerSetup;

abstract class AbstractApplication implements ApplicationInterface
{

    /**
     * @var string
     */
    protected $bootstrapDirectory;

    /**
     * @var BootstrapRegistry
     */
    protected $bootstrapRegistry;

    /**
     * @var ApplicationConfig
     */
    protected $applicationConfig;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * HttpApplication constructor.
     * @param string $bootstrapDirectory
     */
    public function __construct(string $bootstrapDirectory)
    {
        $this->bootstrapDirectory = IncludeHelper::normalizePath($bootstrapDirectory);
    }

    final protected function init(): void
    {
        $this->createApplicationConfig();
        $this->bootstrap();
        $this->createServiceManager();
    }

    /**
     *
     */
    public abstract function run(): void;

    /**
     *
     */
    final protected function createApplicationConfig(): void
    {
        $applicationConfigurator = new ApplicationConfigurator($this->bootstrapDirectory);
        $this->configureDefaultBootstrap($applicationConfigurator);

        if (\file_exists($this->bootstrapDirectory . 'application.php')) {
            IncludeHelper::include(
                $this->bootstrapDirectory . 'application.php',
                ['applicationConfigurator' => $applicationConfigurator]
            );
        }

        $modules = [];
        $modulesConfig = $applicationConfigurator->getModules();
        foreach ($applicationConfigurator->getModules() as $moduleClass) {
            $modules[] = new $moduleClass;
        }

        $this->bootstrapRegistry = new BootstrapRegistry($modules);

        foreach ($modules as $module) {
            if (\file_exists($module->getBootstrapDirectory() . 'application.php')) {
                IncludeHelper::include(
                    $this->bootstrapDirectory . 'application.php',
                    ['applicationConfigurator' => $applicationConfigurator]
                );
            }
        }

        /*
         * submodules are not loaded at the moment. To prevent a config which is inconsistent with
         * the modules loaded the config is overwritten.
         * Can be removed when recursive module loading is implemented
         */
        $applicationConfigurator->setModules($modulesConfig);

        $this->applicationConfig = $applicationConfigurator->getApplicationConfig();
    }

    /**
     *
     */
    protected function bootstrap(): void
    {
        foreach ($this->applicationConfig->getBootstrapQueue() as $bootstrapItem) {
            (new $bootstrapItem())->bootstrap($this->applicationConfig, $this->bootstrapRegistry);
        }
    }

    /**
     *
     */
    protected function createServiceManager(): void
    {
        $serviceManagerConfig = $this->bootstrapRegistry->get(ServiceManagerConfig::class);

        $this->serviceManager = new ServiceManager(
            $serviceManagerConfig,
            new ServiceManagerSetup(
                $this->applicationConfig->getPersistCacheDirectory() . 'servicemanager/',
                !$this->applicationConfig->isDevelopment()
            ),
            $this->bootstrapRegistry->getServices()
        );
    }

    protected abstract function configureDefaultBootstrap(ApplicationConfigurator $applicationConfigurator): void;

}
