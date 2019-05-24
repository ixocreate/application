<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Application;

use Ixocreate\Application\Bootstrap\BootstrapItemInclude;
use PHPUnit\Framework\TestCase;

class IncludeHelperTest extends TestCase
{
    private $includeTestFile;

    public function setUp()
    {
        $this->includeTestFile = \realpath(__DIR__ . '/../bootstrap/include_test.php');
    }

    public function testInclude()
    {
        BootstrapItemInclude::include($this->includeTestFile);

        $this->assertTrue(\in_array($this->includeTestFile, \get_included_files()));
    }

    public function testVariableSet()
    {
        $testObj = new \stdClass();
        $testObj->check = false;

        BootstrapItemInclude::include($this->includeTestFile, ['testObj' => $testObj]);

        $this->assertTrue($testObj->check);
    }
}
