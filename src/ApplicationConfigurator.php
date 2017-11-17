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

final class ApplicationConfigurator
{
    /**
     * @var bool
     */
    private $development = true;

    /**
     * @var \SplPriorityQueue
     */
    private $bootstrapQueue;

    /**
     * @var string
     */
    private $persistCacheDirectory = "resource/application/";

    /**
     * @var string
     */
    private $cacheDirectory = "data/cache/application/";

    /**
     * @var string
     */
    private $bootstrapDirectory;

    /**
     * ApplicationConfigurator constructor.
     * @param string $bootstrapDirectory
     */
    public function __construct(string $bootstrapDirectory)
    {
        $this->bootstrapQueue = new \SplPriorityQueue();

        if (empty($bootstrapDirectory)) {
            $bootstrapDirectory = ".";
        }
        $this->bootstrapDirectory = \rtrim($bootstrapDirectory, '/') . '/';
    }

    /**
     * @param bool $development
     */
    public function setDevelopment(bool $development): void
    {
        $this->development = $development;
    }

    /**
     * @param string $persistCacheDirectory
     */
    public function setPersistCacheDirectory(string $persistCacheDirectory): void
    {
        if (empty($persistCacheDirectory)) {
            $persistCacheDirectory = ".";
        }

        $this->persistCacheDirectory = \rtrim($persistCacheDirectory, '/') . '/';
    }

    /**
     * @param string $cacheDirectory
     */
    public function setCacheDirectory(string $cacheDirectory): void
    {
        if (empty($cacheDirectory)) {
            $cacheDirectory = ".";
        }

        $this->cacheDirectory = \rtrim($cacheDirectory, '/') . '/';
    }

    /**
     * @param string $bootstrapItem
     * @param int $priority
     */
    public function addBootstrapItem(string $bootstrapItem, int $priority = 100): void
    {
        //TODO interface check
        $this->bootstrapQueue->insert($bootstrapItem, $priority);
    }

    /**
     * @return ApplicationConfig
     */
    public function getApplicationConfig(): ApplicationConfig
    {
        $bootstrapQueue = [];
        if ($this->bootstrapQueue->count() > 0) {
            $this->bootstrapQueue->top();
            while ($this->bootstrapQueue->valid()) {
                $bootstrapQueue[] = $this->bootstrapQueue->extract();
            }
        }


        return new ApplicationConfig([
            'development'               => $this->development,
            'persistCacheDirectory'     => $this->persistCacheDirectory,
            'cacheDirectory'            => $this->cacheDirectory,
            'bootstrapQueue'            => $bootstrapQueue,
            'bootstrapDirectory'        => $this->bootstrapDirectory,
        ]);
    }
}
