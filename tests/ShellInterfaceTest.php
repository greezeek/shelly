<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..'  .DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
class ShellInterfaceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider writeDataProvider
     */
    public function testWrite($str, $res)
    {
        $si = new \Shelly\ShellInterface();
        $this->expectOutputString($res);
        $si->write($str);
    }
    /**
     * @dataProvider incorrectWriteDataProvider
     */
    public function testWriteInvalidData($data) {
        $si = new \Shelly\ShellInterface();
        $this->setExpectedException('Exception', \Shelly\ShellInterface::STRING_REQUIRED);
        $si->write($data);
    }

    public function writeDataProvider()
    {
        return [
            ['a', 'a'],
            ['', ''],
        ];

    }
    public function incorrectWriteDataProvider()
    {
        return [
            [false],
            [[]],
            [new stdClass()]
        ];
    }
}