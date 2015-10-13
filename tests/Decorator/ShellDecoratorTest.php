<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
class ShellDecoratorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider initDecoratorDataProvider
     */
    public function testInitDecorator($eol, $fg, $bg, $bold, $exp)
    {
        $i = new \Shelly\ColorShellInterface();
        $d = new \Shelly\Decorator\ShellDecorator($i, ['eol' => $eol, 'fg'=>$fg, 'bg' => $bg, 'bold' => $bold]);

        $this->assertAttributeEquals([
            'eol' => $eol,
            'fg' => $fg,
            'bg' => $bg,
            'bold' => $bold
        ],
            'options',
            $d);
        $this->assertEquals($d->decorate('test'),$exp);

    }

    public function initDecoratorDataProvider()
    {
        return [
            [true, 'normal', 'red', true, "\033[1;41;0mtest\033[0m" . PHP_EOL],
            [true, 'blue', 'yellow', true, "\033[1;43;34mtest\033[0m" . PHP_EOL],
            [false, 'normal', 'red', false, "\033[41;0mtest\033[0m"],
            [null, 'normal', 'red', null, "\033[41;0mtest\033[0m"],
            [false, 'normal', 'red', true, "\033[1;41;0mtest\033[0m"],
            [null, 'normal', 'red', true, "\033[1;41;0mtest\033[0m"],
            [true, 'normal', 'red', false, "\033[41;0mtest\033[0m" . PHP_EOL],
            [true, 'normal', 'red', false, "\033[41;0mtest\033[0m" . PHP_EOL],
            [true, 'normal', false, false, "\033[0mtest\033[0m" . PHP_EOL],
            [true, 'normal', null, false, "\033[0mtest\033[0m" . PHP_EOL],
        ];
    }


    public function testInitDecoratorUnknownOption()
    {
        $i = new \Shelly\ColorShellInterface();
        $this->setExpectedException('Exception', \Shelly\Decorator\ShellDecorator::MSG_UNKNOWN_OPTION);
        $d = new \Shelly\Decorator\ShellDecorator($i, ['eol' => true, 'fg'=>'normal', 'bg' => 'red', 'bold' => true, 'unknown' => 'eee']);



    }

    /**
     * @dataProvider initDecoratorBadParameterDataProvider
     */
    public function testInitDecoratorBadParameter($eol, $fg, $bg, $bold)
    {
        $i = new \Shelly\ColorShellInterface();
        $this->setExpectedException('Exception', \Shelly\Decorator\ShellDecorator::MSG_INVALID_OPTION_VALUE);
        $d = new \Shelly\Decorator\ShellDecorator($i, ['eol' => $eol, 'fg'=>$fg, 'bg' => $bg, 'bold' => $bold]);
    }

    public function initDecoratorBadParameterDataProvider()
    {
        return [
            [true, 'noneq', 'red', null, "\033[1;41;0mtest\033[0m" . PHP_EOL],
            [null, 'blue', 'noneq', true, "\033[1;43;34mtest\033[0m" . PHP_EOL],
        ];
    }

    /**
     * @dataProvider initDecoratorNoColorDataProvider
     */
    public function testDecoratorDisabledColor($eol, $fg, $bg, $bold, $exp)
    {
        $i = new \Shelly\ColorShellInterface();
        $i->disableColour();
        $d = new \Shelly\Decorator\ShellDecorator($i, ['eol' => $eol, 'fg'=>$fg, 'bg' => $bg, 'bold' => $bold]);

        $this->assertEquals($d->decorate('test'),$exp);
    }

    public function initDecoratorNoColorDataProvider()
    {
        return [
            [true, 'normal', 'red', true, "test" . PHP_EOL],
            [true, 'blue', 'yellow', true, "test" . PHP_EOL],
            [false, 'normal', 'red', false, "test"],
            [false, 'normal', 'red', true, "test"],
            [true, 'normal', 'red', false, "test" . PHP_EOL],
        ];
    }

    /**
     * @dataProvider getColorDataProvider
     */
    public function testGetColor($fg, $bg, $bold, $exp)
    {

        $this->assertEquals(\Shelly\Decorator\ShellDecorator::sGetColor($fg, $bg, $bold),$exp);
    }


    /**
     * @dataProvider nonexGetColorDataProvider
     */
    public function testGetNeColorStamp($fg, $bg, $bold)
    {
        $this->setExpectedException('Exception', \Shelly\Decorator\ShellDecorator::MSG_COLOR_NOT_EXISTS);

        \Shelly\Decorator\ShellDecorator::sGetColor($fg, $bg, $bold);
    }


    public function getColorDataProvider()
    {
        return [
            ['normal', 'red', true, "1;41;0"],
            ['blue', 'yellow', true, "1;43;34"],
            ['normal', 'red', false, "41;0"],
            ['normal', 'red', true, "1;41;0"],
            ['normal', 'red', false, "41;0"]
        ];
    }

    public function nonexGetColorDataProvider()
    {
        return [
            ['nonex', 'nonex', false],
            ['nonex', 'red', false],
            ['red', 'nonex', false],
        ];
    }

    /**
     * @dataProvider colorStampDataProvider
     */
    public function testPrintColorStamp($val, $exp)
    {
        $this->assertEquals(\Shelly\Decorator\ShellDecorator::printColourStamp($val), $exp);
    }

    public function colorStampDataProvider()
    {
        return [
            ["1;41;0", "\033[1;41;0m"],
            ["1;43;34", "\033[1;43;34m"],
            ["41;31", "\033[41;31m"],
            ["1;41;0", "\033[1;41;0m"],
            ["41;0", "\033[41;0m"]
        ];
    }


    /**
     * @dataProvider toStringDecoratorDataProvider
     */
    public function testToStringDecorator($eol, $fg, $bg, $bold, $exp)
    {
        $i = new \Shelly\ColorShellInterface();
        $d = new \Shelly\Decorator\ShellDecorator($i, ['eol' => $eol, 'fg'=>$fg, 'bg' => $bg, 'bold' => $bold]);

        $this->assertEquals((string)$d,$exp);

    }

    public function toStringDecoratorDataProvider()
    {
        return [
            [true, 'normal', 'red', true, "1;41;0"],
            [true, 'blue', 'yellow', true, "1;43;34"],
            [false, 'normal', 'red', false, "41;0"],
            [false, 'normal', 'red', true, "1;41;0"],
            [true, 'normal', 'red', false, "41;0"],

        ];
    }



    /**
     * @dataProvider toStringDecoratorBadParamDataProvider
     */
    public function testToStringDecoratorBadParam($eol, $fg, $bg, $bold)
    {
        $i = new \Shelly\ColorShellInterface();

        $this->setExpectedException('Exception', \Shelly\Decorator\ShellDecorator::MSG_INVALID_OPTION_VALUE);
        $d = new \Shelly\Decorator\ShellDecorator($i, ['eol' => $eol, 'fg'=>$fg, 'bg' => $bg, 'bold' => $bold]);



    }

    public function toStringDecoratorBadParamDataProvider()
    {
        return [
            [true, 'abnormal', 'red', true],
            [true, 'blues', 'yellow', true],
            [false, 'normal', 'reds', false],
            [false, 'normfal', 'red', true],

        ];
    }

}
