<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Bootstrap;

use Ixocreate\Application\Bootstrap\BootstrapItemInclude;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Application\Bootstrap\BootstrapItemInclude
*/
class BootstrapItemIncludeTest extends TestCase
{
    public function testInclude()
    {
        $vfsBootstrap = vfsStream::setup();
        $configFile = vfsStream::newFile('include.php')->at($vfsBootstrap);
        $configFile->write(
            <<<'EOF'
<?php

$test->assertEquals('bar', $foo);

EOF
        );

        BootstrapItemInclude::include($vfsBootstrap->getChild('include.php')->url(), ['test' => $this, 'foo' => 'bar']);
    }

    public function testNormalizePath()
    {
        $this->assertEquals('./', BootstrapItemInclude::normalizePath(''));
        $this->assertEquals('/', BootstrapItemInclude::normalizePath('/'));
        $this->assertEquals('/', BootstrapItemInclude::normalizePath('//'));
        $this->assertEquals('directory/', BootstrapItemInclude::normalizePath('directory'));
        $this->assertEquals('directory/', BootstrapItemInclude::normalizePath('directory/'));
        $this->assertEquals('directory/', BootstrapItemInclude::normalizePath('directory//'));
        $this->assertEquals('/directory/', BootstrapItemInclude::normalizePath('/directory'));
        $this->assertEquals('/directory/', BootstrapItemInclude::normalizePath('/directory/'));
        $this->assertEquals('/directory/', BootstrapItemInclude::normalizePath('/directory//'));
    }
}
