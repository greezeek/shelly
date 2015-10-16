<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..'  .DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
class PaletteTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider colorAliasDataProvider
     */
    public function testGetColorByAlias($alias, $expectedColor)
    {
        $palette = new Shelly\Palette();
        $this->assertEquals($expectedColor, $palette->getColorByAlias($alias));

    }

    public function colorAliasDataProvider()
    {
        return [
            ['black', 30],
            ['red', 31],
            ['green', 32],
            ['yellow', 33],
            ['blue', 34],
            ['magenta', 35],
            ['cyan', 36],
            ['white', 37],
            ['normal', 0]
        ];
    }

    /**
     * @dataProvider bgColorAliasDataProvider
     */
    public function testGetBgColorByAlias($alias, $expectedColor)
    {
        $palette = new Shelly\Palette();
        $this->assertEquals($expectedColor, $palette->getBgColorByAlias($alias));

    }

    public function bgColorAliasDataProvider()
    {
        return [
            ['black', 40],
            ['red', 41],
            ['green', 42],
            ['yellow', 43],
            ['blue', 44],
            ['magenta', 45],
            ['cyan', 46],
            ['white', 47],
        ];
    }

    /**
     * @dataProvider printColorStampDataProvider
     */
    public function testPrintColorStamp($fg, $bg, $bold, $expectedString)
    {
        $palette = new Shelly\Palette();

        $this->assertEquals($expectedString, $palette->printColourStamp($fg, $bg, $bold));
    }

    public function printColorStampDataProvider()
    {
        return [
            ['black','black', false, "\033[40;30m"],
            ['red','red', false, "\033[41;31m"],
            ['green','green', false, "\033[42;32m"],
            ['yellow','yellow', false, "\033[43;33m"],
            ['blue','blue', true, "\033[1;44;34m"],
            ['magenta','magenta', 1, "\033[1;45;35m"],
            ['cyan','cyan', null, "\033[46;36m"],
            ['white',false, false, "\033[37m"],
            ['normal',false, 1, "\033[1;0m"],
        ];
    }

}