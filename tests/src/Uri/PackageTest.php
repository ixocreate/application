<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\ProjectUri;

use Ixocreate\Application\Service\Configurator\ConfiguratorRegistryInterface;
use Ixocreate\Application\Service\Registry\ServiceRegistryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\Application\Uri\Bootstrap\ProjectUriBootstrapItem;
use Ixocreate\Application\Uri\Package;
use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase
{
    public function testPackage()
    {
        $configuratorRegistry = $this->getMockBuilder(ConfiguratorRegistryInterface::class)->getMock();
        $configuratorRegistry->method('get')->willThrowException(new \InvalidArgumentException('Fail: Package::configure not empty!'));
        $configuratorRegistry->method('add')->willThrowException(new \InvalidArgumentException('Fail: Package::configure not empty!'));

        $serviceRegistry = $this->getMockBuilder(ServiceRegistryInterface::class)->getMock();
        $serviceRegistry->method('get')->willThrowException(new \InvalidArgumentException('Fail: Package::addService not empty!'));
        $serviceRegistry->method('add')->willThrowException(new \InvalidArgumentException('Fail: Package::addService not empty!'));

        $serviceManager = $this->getMockBuilder(ServiceManagerInterface::class)->getMock();
        $serviceManager->method('get')->willThrowException(new \InvalidArgumentException('Fail: Package::boot not empty!'));
        $serviceManager->method('build')->willThrowException(new \InvalidArgumentException('Fail: Package::boot not empty!'));
        $serviceManager->method('getServiceManagerConfig')->willThrowException(new \InvalidArgumentException('Fail: Package::boot not empty!'));
        $serviceManager->method('getServiceManagerSetup')->willThrowException(new \InvalidArgumentException('Fail: Package::boot not empty!'));
        $serviceManager->method('getFactoryResolver')->willThrowException(new \InvalidArgumentException('Fail: Package::boot not empty!'));
        $serviceManager->method('getServices')->willThrowException(new \InvalidArgumentException('Fail: Package::boot not empty!'));

        $package = new Package();

        $package->configure($configuratorRegistry);
        $package->addServices($serviceRegistry);
        $package->boot($serviceManager);

        $this->assertSame([ProjectUriBootstrapItem::class], $package->getBootstrapItems());
        $this->assertNull($package->getConfigProvider());
        $this->assertNull($package->getConfigDirectory());
        $this->assertNull($package->getDependencies());

        $this->assertSame(\dirname(\dirname(__DIR__)) . '/src/../bootstrap', $package->getBootstrapDirectory());
    }
}
