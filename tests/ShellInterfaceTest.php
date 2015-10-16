<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..'  .DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
class ShellInterfaceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ShellInterface::write
     * @dataProvider writeDataProvider
     */
    public function testWrite($str, $res)
    {
        $si = new \Shelly\ShellInterface();
        $this->expectOutputString($res);
        $si->write($str);
    }
    /**
     * @covers ShellInterface::write
     * @dataProvider incorrectWriteDataProvider
     */
    public function testWriteInvalidData($data) {
        $si = new \Shelly\ShellInterface();
        try {
            $si->write($data);
        } catch (Exception $e) {
            $this->assertEquals(\Shelly\ShellInterface::MSG_STRING_REQUIRED, $e->getMessage());
        }
        $this->expectOutputString('');
    }

    public function writeDataProvider()
    {
        return [
            ['a', 'a'],
            ['', ''],
            ['1', '1'],
            ['0', '0'],
        ];

    }
    public function incorrectWriteDataProvider()
    {
        return [
            [false],
            [[]],
            [new stdClass()],
            [1],
            [0]
        ];
    }


    /**
     * @covers ShellInterface::read
     */
    public function testRead()
    {

        $this->markTestSkipped('I realy dont know, how to test STDIN');

    }

    /**
     * @covers ShellInterface::getCols
     */
    public function testGetCols()
    {
        if($exp = (int)@`tput cols`) {
            $si = new \Shelly\ShellInterface();
            $this->assertEquals($exp, $si->getCols());
        } else {
            $this->markTestSkipped('Tput inaccessible');
        }
    }


    /**
     * @covers ShellInterface::getRows
     */
    public function testGetRows()
    {
        if($exp = (int)@`tput lines`) {
            $si = new \Shelly\ShellInterface();
            $this->assertEquals($exp, $si->getRows());
        } else {
            $this->markTestSkipped('Tput inaccessible');
        }
    }


}