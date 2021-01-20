<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Service;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Package\ConfigureAwareInterface;
use Ixocreate\Application\Service\ServiceHandler;
use PHPUnit\Framework\TestCase;

class ServiceHandlerTest extends TestCase
{
    public function testServiceCreation()
    {
        $config = $this->createMock(ApplicationConfig::class);
        $config
            ->method('isDevelopment')
            ->willReturn(true);

        $serviceHandler = new ServiceHandler();
        $registry = $serviceHandler->load($config);

        $this->assertEmpty($registry->all());
    }

    public function testConfigAwarePackages()
    {
        $configPackage = $this->createMock(ConfigureAwareInterface::class);
        $configPackage
            ->expects($this->once())
            ->method('configure');

        $config = $this->createMock(ApplicationConfig::class);
        $config
            ->method('isDevelopment')
            ->willReturn(true);
        $config->method('getPackages')
            ->willReturn([$configPackage]);

        $serviceHandler = new ServiceHandler();
        $registry = $serviceHandler->load($config);
    }
}
