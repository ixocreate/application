<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application\Bootstrap\Console;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Application\Bootstrap\Console\BootstrapGenerateCommand;
use Ixocreate\Misc\Application\BootstrapItemDummy;
use Ixocreate\Misc\Application\BootstrapItemSecondDummy;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @covers \Ixocreate\Application\Bootstrap\Console\BootstrapGenerateCommand
 */
class BootstrapGenerateCommandTest extends TestCase
{
    private $config;

    private $vfs;

    protected function setUp(): void
    {
        $this->vfs = vfsStream::setup();
        vfsStream::copyFromFileSystem('tests/bootstrap', $this->vfs);

        $configurator = new ApplicationConfigurator($this->vfs->url());
        $configurator->addBootstrapItem(BootstrapItemDummy::class);
        $configurator->addBootstrapItem(BootstrapItemSecondDummy::class);
        $this->config = new ApplicationConfig($configurator);
    }

    public function testDefaultExecute()
    {
        $input = new ArrayInput([
            'file' => 'secondDummy.php',
        ]);
        $output = new BufferedOutput();

        $command = new BootstrapGenerateCommand($this->config);
        $exitCode = $command->run($input, $output);

        $this->assertTrue($this->vfs->hasChild('secondDummy.php'));
        $this->assertEquals(0, $exitCode);
    }

    public function testEnvExecute()
    {
        $input = new ArrayInput([
            'file' => 'secondDummy.php',
            'env' => 'production',
        ]);
        $output = new BufferedOutput();

        $command = new BootstrapGenerateCommand($this->config);
        $exitCode = $command->run($input, $output);

        $this->assertTrue($this->vfs->hasChild('production/secondDummy.php'));
        $this->assertEquals(0, $exitCode);
    }

    public function testEnvCleanupExecute()
    {
        $output = new BufferedOutput();
        $command = new BootstrapGenerateCommand($this->config);

        $command->run(new ArrayInput([
            'file' => 'secondDummy.php',
            'env' => 'production/ ',
        ]), $output);
        $this->assertTrue($this->vfs->hasChild('production/secondDummy.php'));
        $this->vfs->getChild('production')->removeChild('secondDummy.php');

        $exitCode = $command->run(new ArrayInput([
            'file' => 'secondDummy.php',
            'env' => "production/\t",
        ]), $output);
        $this->assertTrue($this->vfs->hasChild('production/secondDummy.php'));
        $this->assertEquals(0, $exitCode);
    }

    public function testMissingFilename()
    {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();

        $this->expectException(RuntimeException::class);

        $command = new BootstrapGenerateCommand($this->config);
        $exitCode = $command->run($input, $output);
        $this->assertEquals(0, $exitCode);
    }

    public function testExistingFile()
    {
        $input = new ArrayInput([
            'file' => 'dummy.php',
            'env' => 'production',
        ]);
        $output = new BufferedOutput();

        $this->expectException(\Exception::class);

        $command = new BootstrapGenerateCommand($this->config);
        $exitCode = $command->run($input, $output);
        $this->assertEquals(0, $exitCode);
    }

    public function testInvalidFilename()
    {
        $input = new ArrayInput([
            'file' => 'not-there.php',
        ]);
        $output = new BufferedOutput();

        $this->expectException(\Exception::class);

        $command = new BootstrapGenerateCommand($this->config);
        $exitCode = $command->run($input, $output);
        $this->assertEquals(0, $exitCode);
    }

    public function testCommandName()
    {
        $command = new BootstrapGenerateCommand($this->config);
        $this->assertEquals('bootstrap:generate', $command->getName());
    }

    public function testCommandNameString()
    {
        $this->assertEquals('bootstrap:generate', BootstrapGenerateCommand::getCommandName());
    }
}
