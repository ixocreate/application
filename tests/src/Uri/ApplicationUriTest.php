<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Uri;

use Ixocreate\Application\Uri\ApplicationUri;
use Ixocreate\Application\Uri\ApplicationUriConfigurator;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;

/**
 * Class ProjectUriTest
 * @package IxocreateTest\ApplicationUri
 */
class ApplicationUriTest extends TestCase
{
    /**
     * @covers \Ixocreate\Application\Uri\ApplicationUri
     */
    public function testMainUri()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');

        $applicationUri = new ApplicationUri($configurator);

        $this->assertEquals(new Uri('https://project-uri.test'), $applicationUri->getMainUri());
        $this->assertEquals(new Uri('https://project-uri.test'), $applicationUri->getMainUrl());
    }

    /**
     *
     */
    public function testAlternativeUris()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->addAlternativeUri('test', 'https://project-uri-2.test');

        $applicationUri = new ApplicationUri($configurator);

        $alternativeUris = [
            'test' => new Uri('https://project-uri-2.test'),
        ];

        $this->assertEquals($alternativeUris, $applicationUri->getAlternativeUris());
        $this->assertEquals($alternativeUris['test'], $applicationUri->getAlternativeUri('test'));
        $this->assertNull($applicationUri->getAlternativeUri('not-found'));
    }

    /**
     *
     */
    public function testPossibleUris()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');
        $configurator->addAlternativeUri('test', 'https://project-uri-2.test');

        $applicationUri = new ApplicationUri($configurator);

        $possibleUris = [
            'test' => new Uri('https://project-uri-2.test'),
            'mainUri' => new Uri('https://project-uri.test'),
        ];

        $this->assertEquals($possibleUris, $applicationUri->getPossibleUrls());
    }

    public function testPossibleUri()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');
        $configurator->addAlternativeUri('test', 'https://project-uri-2.test');

        $applicationUri = new ApplicationUri($configurator);

        $this->assertEquals('https://project-uri.test', $applicationUri->getPossibleUri('mainUri'));
        $this->assertEquals('https://project-uri-2.test', $applicationUri->getPossibleUri('test'));
        $this->assertNull($applicationUri->getPossibleUri('unkown'));
    }

    public function testFullRedirectUri()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->addFullRedirectDomain('https://project-uri.test');
        $configurator->addFullRedirectDomain('https://project-uri2.test');

        $applicationUri = new ApplicationUri($configurator);
        $this->assertEquals(['https://project-uri.test', 'https://project-uri2.test'], $applicationUri->getFullRedirectDomains());
    }

    public function testIsValidUrl()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');
        $configurator->addAlternativeUri('test-1', 'https://project-uri-1.test');
        $configurator->addAlternativeUri('test-2', 'https://project-uri-2.test/subPath');

        $applicationUri = new ApplicationUri($configurator);

        $this->assertTrue($applicationUri->isValidUrl(new Uri('https://project-uri.test')));
        $this->assertTrue($applicationUri->isValidUrl(new Uri('https://project-uri-1.test')));
        $this->assertFalse($applicationUri->isValidUrl(new Uri('https://project-uri-not-set.test')));
        $this->assertFalse($applicationUri->isValidUrl(new Uri('http://project-uri.test')));
        $this->assertFalse($applicationUri->isValidUrl(new Uri('https://project-uri.test:8080')));
        $this->assertFalse($applicationUri->isValidUrl(new Uri('https://project-uri-2.test/path')));
        $this->assertFalse($applicationUri->isValidUrl(new Uri('https://project-uri-2.test/withASpecialInvalidPath')));
    }

    public function testGetPathWithoutBase()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');
        $configurator->addAlternativeUri('test-2', 'https://project-uri-2.test/subPath/');

        $applicationUri = new ApplicationUri($configurator);

        $this->assertEquals('', $applicationUri->getPathWithoutBase(new Uri('https://project-uri.test')));
        $this->assertEquals('/withPath', $applicationUri->getPathWithoutBase(new Uri('https://project-uri.test/withPath')));

        $this->assertEquals('', $applicationUri->getPathWithoutBase(new Uri('https://project-uri-not-found.test')));
        $this->assertEquals('', $applicationUri->getPathWithoutBase(new Uri('http://project-uri.test')));
        $this->assertEquals('', $applicationUri->getPathWithoutBase(new Uri('https://project-uri.test:8080')));
        $this->assertEquals('', $applicationUri->getPathWithoutBase(new Uri('https://project-uri-2.test/withASpecialInvalidPath')));
        $this->assertEquals('/pathToGlory', $applicationUri->getPathWithoutBase(new Uri('https://project-uri-2.test/subPath/pathToGlory')));
    }

    /**
     * @covers \Ixocreate\Application\Uri\ApplicationUri::serialize
     * @covers \Ixocreate\Application\Uri\ApplicationUri::unserialize
     */
    public function testSerialization()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');
        $configurator->addAlternativeUri('test', 'https://project-uri-2.test');

        $applicationUri = new ApplicationUri($configurator);

        $serialized = \serialize($applicationUri);
        $restoredConfig = \unserialize($serialized);

        $this->assertEquals($applicationUri->getMainUri(), $restoredConfig->getMainUri());
        $this->assertEquals($applicationUri->getAlternativeUris(), $restoredConfig->getAlternativeUris());
    }
}
