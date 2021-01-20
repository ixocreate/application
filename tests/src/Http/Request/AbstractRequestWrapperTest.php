<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Http\Request;

use Ixocreate\Application\Http\Request\AbstractRequestWrapper;
use PHPUnit\Framework\TestCase;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFile;
use Laminas\Diactoros\Uri;

/**
 * @covers \Ixocreate\Application\Http\Request\AbstractRequestWrapper
 */
class AbstractRequestWrapperTest extends TestCase
{
    public function testAttribute()
    {
        $request = new ServerRequest();
        $request = $request->withAttribute('attr1', 'value1');

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newWrapper = $wrapper->withAttribute('attr2', 'value2');
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals(['attr1' => 'value1', 'attr2' => 'value2'], $newWrapper->getAttributes());
        $this->assertEquals('value2', $newWrapper->getAttribute('attr2'));
        $this->assertEquals(['attr1' => 'value1'], $request->getAttributes());

        $withoutWrapper = $wrapper->withoutAttribute('attr1');
        $this->assertEquals([], $withoutWrapper->getAttributes());
    }

    public function testBody()
    {
        $originalBody = (new StreamFactory())->createStream('someBody');
        $request = new ServerRequest();
        $request = $request->withBody($originalBody);

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newBody = (new StreamFactory())->createStream('someOtherBody');
        $newWrapper = $wrapper->withBody($newBody);
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals($newBody, $newWrapper->getBody());
        $this->assertSame($originalBody, $request->getBody());
    }

    public function testRequestTarget()
    {
        $originalTarget = 'target';
        $request = new ServerRequest();
        $request = $request->withRequestTarget($originalTarget);

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newTarget = 'newTarget';
        $newWrapper = $wrapper->withRequestTarget($newTarget);
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals($newTarget, $newWrapper->getRequestTarget());
        $this->assertSame($originalTarget, $request->getRequestTarget());
    }

    public function testProtocolVersion()
    {
        $originalVersion = '1.0';
        $request = new ServerRequest();
        $request = $request->withProtocolVersion($originalVersion);

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newVersion = '1.1';
        $newWrapper = $wrapper->withProtocolVersion($newVersion);
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals($newVersion, $newWrapper->getProtocolVersion());
        $this->assertSame($originalVersion, $request->getProtocolVersion());
    }

    public function testParsedBody()
    {
        $originalData = ['data1' => 'value1'];
        $request = new ServerRequest();
        $request = $request->withParsedBody($originalData);

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newData = ['data2' => 'value2'];
        $newWrapper = $wrapper->withParsedBody($newData);
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals($newData, $newWrapper->getParsedBody());
        $this->assertSame($originalData, $request->getParsedBody());
    }

    public function testQueryParams()
    {
        $originalData = ['data1' => 'value1'];
        $request = new ServerRequest();
        $request = $request->withQueryParams($originalData);

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newData = ['data2' => 'value2'];
        $newWrapper = $wrapper->withQueryParams($newData);
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals($newData, $newWrapper->getQueryParams());
        $this->assertSame($originalData, $request->getQueryParams());
    }

    public function testCookieParams()
    {
        $originalParams = ['data1' => 'value1'];
        $request = new ServerRequest();
        $request = $request->withCookieParams($originalParams);

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newParams = ['data2' => 'value2'];
        $newWrapper = $wrapper->withCookieParams($newParams);
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals($newParams, $newWrapper->getCookieParams());
        $this->assertSame($originalParams, $request->getCookieParams());
    }

    public function testUri()
    {
        $originalUri = new Uri('https://host.test/somePath');
        $request = new ServerRequest();
        $request = $request->withUri($originalUri);

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newUri = new Uri('http://newhost.test/somePath2');
        $newWrapper = $wrapper->withUri($newUri);
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals($newUri, $newWrapper->getUri());
        $this->assertSame($originalUri, $request->getUri());
    }

    public function testUploadedFiles()
    {
        $originalFiles = [
            new UploadedFile('upload.txt', 1000, 0),
        ];
        $request = new ServerRequest();
        $request = $request->withUploadedFiles($originalFiles);

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $files = [
            new UploadedFile('upload.txt', 1001, 0),
            new UploadedFile('upload2.txt', 1002, 0),
        ];
        $newWrapper = $wrapper->withUploadedFiles($files);
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals($files, $newWrapper->getUploadedFiles());
        $this->assertSame($originalFiles, $request->getUploadedFiles());
    }

    public function testMethod()
    {
        $request = new ServerRequest();
        $request = $request->withMethod('post');

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newWrapper = $wrapper->withMethod('get');
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());
        $this->assertEquals('get', $newWrapper->getMethod());
        $this->assertSame('post', $request->getMethod());
    }

    public function testServerParams()
    {
        $request = new ServerRequest(['param1' => 'value1']);

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $this->assertEquals(['param1' => 'value1'], $wrapper->getServerParams());
    }

    public function testHeaders()
    {
        $request = new ServerRequest();
        $request = $request->withHeader('x-foo', 'bar');
        $request = $request->withHeader('x-foo2', 'baz');
        $request = $request->withHeader('x-foo3', 'bay');

        $wrapper = new class($request) extends AbstractRequestWrapper {
        };

        $newWrapper = $wrapper->withHeader('x-foo', 'bar2');
        $newWrapper = $newWrapper->withAddedHeader('x-foo2', 'baz2');
        $newWrapper = $newWrapper->withoutHeader('x-foo3');
        $this->assertNotSame($newWrapper, $wrapper);
        $this->assertSame($request, $newWrapper->originalRequest());

        $this->assertEquals(['x-foo' => ['bar2'], 'x-foo2' => ['baz', 'baz2']], $newWrapper->getHeaders());
        $this->assertEquals(['bar2'], $newWrapper->getHeader('x-foo'));
        $this->assertEquals('bar2', $newWrapper->getHeaderLine('x-foo'));
        $this->assertEquals(['baz', 'baz2'], $newWrapper->getHeader('x-foo2'));
        $this->assertTrue($newWrapper->hasHeader('x-foo2'));
        $this->assertEquals(['bar'], $request->getHeader('x-foo'));
    }
}
