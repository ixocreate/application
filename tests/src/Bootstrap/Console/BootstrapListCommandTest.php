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
use Ixocreate\Application\Bootstrap\Console\BootstrapListCommand;
use Ixocreate\Misc\Application\BootstrapDummy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @covers \Ixocreate\Application\Bootstrap\Console\BootstrapListCommand
 */
class BootstrapListCommandTest extends TestCase
{
    private $config;

    protected function setUp()
    {
        $configurator = new ApplicationConfigurator('tests/bootstrap');
        $configurator->addBootstrapItem(BootstrapDummy::class);
        $this->config = new ApplicationConfig($configurator);
    }

    public function testExecute()
    {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();

        $command = new BootstrapListCommand($this->config);
        $command->run($input, $output);

        $listOutput = <<<EOL
 ----------- -------- ------------ 
  File        Global   production  
 ----------- -------- ------------ 
  dummy.php   Unused   Used        
 ----------- -------- ------------ 


EOL;

        $this->assertEquals($listOutput, $output->fetch());
    }

    public function testCommandName()
    {
        $command = new BootstrapListCommand($this->config);
        $this->assertEquals('bootstrap:list', $command->getName());
    }

    public function testCommandNameString()
    {
        $this->assertEquals('bootstrap:list', BootstrapListCommand::getCommandName());
    }
}
