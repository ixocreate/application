<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\ProjectUri;

use Ixocreate\Application\Uri\ApplicationUriConfigurator;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Uri;

/**
 * Class ProjectUriTest
 * @package IxocreateTest\ApplicationUri
 */
class ProjectUriTest extends TestCase
{
    /**
     * @covers \Ixocreate\Application\Uri\ApplicationUri
     */
    public function testMainUri()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');

        $projectUri = new Uri($configurator);

        $this->assertEquals(new Uri('https://project-uri.test'), $projectUri->getMainUri());
        $this->assertEquals(new Uri('https://project-uri.test'), $projectUri->getMainUrl());
    }

    /**
     *
     */
    public function testAlternativeUris()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->addAlternativeUri('test', 'https://project-uri-2.test');

        $projectUri = new Uri($configurator);

        $alternativeUris = [
            'test' => new Uri('https://project-uri-2.test'),
        ];

        $this->assertEquals($alternativeUris, $projectUri->getAlternativeUris());
        $this->assertEquals($alternativeUris['test'], $projectUri->getAlternativeUri('test'));
        $this->assertNull($projectUri->getAlternativeUri('not-found'));
    }

    /**
     *
     */
    public function testPossibleUrls()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');
        $configurator->addAlternativeUri('test', 'https://project-uri-2.test');

        $projectUri = new Uri($configurator);

        $possibleUris = [
            'test' => new Uri('https://project-uri-2.test'),
            'mainUri' => new Uri('https://project-uri.test'),
        ];

        $this->assertEquals($possibleUris, $projectUri->getPossibleUrls());
    }

    public function testIsValidUrl()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');
        $configurator->addAlternativeUri('test-1', 'https://project-uri-1.test');
        $configurator->addAlternativeUri('test-2', 'https://project-uri-2.test/subPath');

        $projectUri = new Uri($configurator);

        $this->assertTrue($projectUri->isValidUrl(new Uri('https://project-uri.test')));
        $this->assertTrue($projectUri->isValidUrl(new Uri('https://project-uri-1.test')));
        $this->assertFalse($projectUri->isValidUrl(new Uri('https://project-uri-not-set.test')));
        $this->assertFalse($projectUri->isValidUrl(new Uri('http://project-uri.test')));
        $this->assertFalse($projectUri->isValidUrl(new Uri('https://project-uri.test:8080')));
        $this->assertFalse($projectUri->isValidUrl(new Uri('https://project-uri-2.test/path')));
        $this->assertFalse($projectUri->isValidUrl(new Uri('https://project-uri-2.test/withASpecialInvalidPath')));
    }

    public function testGetPathWithoutBase()
    {
        $configurator = new ApplicationUriConfigurator();
        $configurator->setMainUri('https://project-uri.test');
        $configurator->addAlternativeUri('test-2', 'https://project-uri-2.test/subPath/');

        $projectUri = new Uri($configurator);

        $this->assertEquals('', $projectUri->getPathWithoutBase(new Uri('https://project-uri.test')));
        $this->assertEquals('/withPath', $projectUri->getPathWithoutBase(new Uri('https://project-uri.test/withPath')));

        $this->assertEquals('', $projectUri->getPathWithoutBase(new Uri('https://project-uri-not-found.test')));
        $this->assertEquals('', $projectUri->getPathWithoutBase(new Uri('http://project-uri.test')));
        $this->assertEquals('', $projectUri->getPathWithoutBase(new Uri('https://project-uri.test:8080')));
        $this->assertEquals('', $projectUri->getPathWithoutBase(new Uri('https://project-uri-2.test/withASpecialInvalidPath')));
        $this->assertEquals('/pathToGlory', $projectUri->getPathWithoutBase(new Uri('https://project-uri-2.test/subPath/pathToGlory')));
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

        $projectUri = new Uri($configurator);

        $serialized = \serialize($projectUri);
        $restoredConfig = \unserialize($serialized);

        $this->assertEquals($projectUri->getMainUri(), $restoredConfig->getMainUri());
        $this->assertEquals($projectUri->getAlternativeUris(), $restoredConfig->getAlternativeUris());
    }
}
