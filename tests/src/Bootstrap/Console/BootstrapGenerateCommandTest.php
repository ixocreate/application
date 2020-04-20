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
use Ixocreate\Misc\Application\BootstrapDummy;
use Ixocreate\Misc\Application\BootstrapSecondDummy;
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

    protected function setUp()
    {
        $this->vfs = vfsStream::setup();
        vfsStream::copyFromFileSystem('tests/bootstrap', $this->vfs);

        $configurator = new ApplicationConfigurator($this->vfs->url());
        $configurator->addBootstrapItem(BootstrapDummy::class);
        $configurator->addBootstrapItem(BootstrapSecondDummy::class);
        $this->config = new ApplicationConfig($configurator);
    }

    public function testDefaultExecute()
    {
        $input = new ArrayInput([
            'file' => 'secondDummy.php',
        ]);
        $output = new BufferedOutput();

        $command = new BootstrapGenerateCommand($this->config);
        $command->run($input, $output);

        $this->assertTrue($this->vfs->hasChild('secondDummy.php'));
    }

    public function testEnvExecute()
    {
        $input = new ArrayInput([
            'file' => 'secondDummy.php',
            'env' => 'production',
        ]);
        $output = new BufferedOutput();

        $command = new BootstrapGenerateCommand($this->config);
        $command->run($input, $output);

        $this->assertTrue($this->vfs->hasChild('production/secondDummy.php'));
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

        $command->run(new ArrayInput([
            'file' => 'secondDummy.php',
            'env' => "production/\t",
        ]), $output);
        $this->assertTrue($this->vfs->hasChild('production/secondDummy.php'));
    }

    public function testMissingFilename()
    {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();

        $this->expectException(RuntimeException::class);

        $command = new BootstrapGenerateCommand($this->config);
        $command->run($input, $output);
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
        $command->run($input, $output);
    }

    public function testInvalidFilename()
    {
        $input = new ArrayInput([
            'file' => 'not-there.php',
        ]);
        $output = new BufferedOutput();

        $this->expectException(\Exception::class);

        $command = new BootstrapGenerateCommand($this->config);
        $command->run($input, $output);
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
