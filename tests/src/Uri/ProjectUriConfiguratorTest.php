<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\ProjectUri;

use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Application\Uri\ApplicationUri;
use Ixocreate\Application\Uri\ApplicationUriConfigurator;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Uri;

class ProjectUriConfiguratorTest extends TestCase
{
    public function testMainUri()
    {
        $configurator = new ApplicationUriConfigurator();
        $this->assertEquals($configurator->getMainUri(), new Uri('/'));

        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');
        $this->assertEquals($configurator->getMainUri(), new Uri('https://project-uri.test'));
    }

    /**
     * @param $uri
     * @param $exception
     * @dataProvider provideMainUriError
     */
    public function testMainUriError($uri, $exception)
    {
        $this->expectException($exception);

        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri($uri);
    }

    public function provideMainUriError()
    {
        return [
            ['ftp://invalid.com', \InvalidArgumentException::class],
        ];
    }

    public function testAlternativeUris()
    {
        $configurator = new ApplicationUriConfigurator();

        $configurator->addAlternativeUri('test-1', 'https://project-uri.test');
        $configurator->addAlternativeUri('test-2', 'http://project-uri-2.test');

        $alternativeUris = [
            'test-1' => new Uri('https://project-uri.test'),
            'test-2' => new Uri('http://project-uri-2.test'),
        ];

        $this->assertEquals($alternativeUris, $configurator->getAlternativeUris());
    }

    public function testRemoveAlternativeUris()
    {
        $configurator = new ApplicationUriConfigurator();

        $configurator->addAlternativeUri('test-1', 'https://project-uri.test');
        $configurator->addAlternativeUri('test-2', 'http://project-uri-2.test');

        $alternativeUris = [
            'test-2' => new Uri('http://project-uri-2.test'),
        ];

        $configurator->removeAlternativeUri('test-1');

        $this->assertEquals($alternativeUris, $configurator->getAlternativeUris());
    }

    public function testRegisterService()
    {
        $serviceRegistry = $this->getMockBuilder(ServiceRegistryInterface::class)->getMock();
        $serviceRegistry->method('get')->willThrowException(new \InvalidArgumentException('Fail: ServiceRegistry:get should not be called!'));

        $serviceRegistry
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo(ApplicationUri::class), $this->isInstanceOf(ApplicationUri::class));

        $configurator = new ApplicationUriConfigurator();
        $configurator->registerService($serviceRegistry);
    }
}
