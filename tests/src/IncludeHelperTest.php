<?php
/**
 * kiwi-suite/application (https://github.com/kiwi-suite/application)
 *
 * @package kiwi-suite/application
 * @see https://github.com/kiwi-suite/application
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace IxocreateTest\Application;

use Ixocreate\Application\IncludeHelper;
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
        IncludeHelper::include($this->includeTestFile);

        $this->assertTrue(\in_array($this->includeTestFile, \get_included_files()));
    }

    public function testVariableSet()
    {
        $testObj = new \stdClass();
        $testObj->check = false;

        IncludeHelper::include($this->includeTestFile, ['testObj' => $testObj]);

        $this->assertTrue($testObj->check);
    }
}
