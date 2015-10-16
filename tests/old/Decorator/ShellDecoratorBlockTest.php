<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

class ShellDecoratorBlockTest extends PHPUnit_Framework_TestCase
{


    /**
     * @dataProvider closestSymbolDataProvider
     */
    public function testFindClosestSymbol($string, $symbol, $position, $exp) {

        $d = new \Shelly\Decorator\ShellDecoratorBlock(new \Shelly\ColorShellInterface(new \Shelly\Palette()), []);

        $r = $d->findClosestSymbol($string, $symbol, $position);

        $this->assertEquals($exp, $r);

    }


    public function closestSymbolDataProvider()
    {
        return [
          ['aaaaaaaa aaaaaaa aaa aaaaaaa aa aaa aa aaaa', ' ', 23, 20],
          ['aaaaaaaa aaaaaaa aaa aaaaaaa aa aaa aa aaaa', ' ', 30, 28],
          ['aaaaaaaa aaaaaaa aaa aaaaaaa aa aaa aa aaaa', '$', 30, 30],
          ['aaaaaaaa aaaaaaa aaa aaaaaaa aa aaa aa aaaa', ' ', 180, 43],
          ['aaaaaaaa aaaaaaa aaa aaaaaaa aa aaa aa aaaa', ' ', 1, false],
        ];
    }


    /**
     * @dataProvider ArrayFixedWidthSaveWordsDataProvider
     */
    public function testArrayFixedWidthSaveWords($str, $len, $res)
    {
        $d = new \Shelly\Decorator\ShellDecoratorBlock(new \Shelly\ColorShellInterface(new \Shelly\Palette()), []);

        $r = $d->arrayFixedWidthSaveWords($str, $len);

        $this->assertEquals($res, $r);
    }

    public function ArrayFixedWidthSaveWordsDataProvider()
    {
        return [
            [
                'This is the end of the world', 10,
                [
                    'This is',
                    'the end',
                    'of the',
                    'world',
                ]
            ],
            [
                'asdasdasdaasdasdasdaasdasdasdaasdasdasda', 10,
                [
                    'asdasdasda',
                    'asdasdasda',
                    'asdasdasda',
                    'asdasdasda',
                ]
            ],
            [
                'asd asdasdaasdasdasdaasdasdasdaasdasdasda', 10,
                [
                    'asd',
                    'asdasdaasd',
                    'asdasdaasd',
                    'asdasdaasd',
                    'asdasda',
                ]
            ],
        ];
    }

    /**
     * @dataProvider initDataProvider
     */
    public function testInit($w, $m, $a, $bold, $fg, $bg,  $text, $exp)
    {
        $d = new \Shelly\Decorator\ShellDecoratorBlock(new \Shelly\ColorShellInterface(new \Shelly\Palette()), [
            'width' => $w,
            'margin' => $m,
            'align' => $a,
            'fg' => $fg,
            'bg' => $bg,
            'bold' => $bold
        ]);

        $this->assertAttributeEquals([
            'width' => $w,
            'margin' => $m,
            'align' => $a,
            'fg' => $fg,
            'bg' => $bg,
            'bold' => $bold
        ],
            'options',
            $d);
        $this->assertEquals($exp,$d->decorate($text));
    }

    public function initDataProvider()
    {
        return [
            [20, 2, \Shelly\Decorator\ShellDecoratorBlock::ALIGN_LEFT, true, 'normal', 'red',
                'test',
                "\033[1;41;0m                    \033[0m" . PHP_EOL.
                "\033[1;41;0m  test              \033[0m" . PHP_EOL.
                "\033[1;41;0m                    \033[0m" . PHP_EOL
            ],
            [30, 1, \Shelly\Decorator\ShellDecoratorBlock::ALIGN_LEFT, true, 'blue', 'yellow',
                'test',
                "\033[1;43;34m                              \033[0m" . PHP_EOL.
                "\033[1;43;34m test                         \033[0m" . PHP_EOL.
                "\033[1;43;34m                              \033[0m" . PHP_EOL
            ],
            [20, 0, \Shelly\Decorator\ShellDecoratorBlock::ALIGN_LEFT, false, 'normal', 'red',
                'test',
                "\033[41;0m                    \033[0m" . PHP_EOL.
                "\033[41;0mtest                \033[0m" . PHP_EOL.
                "\033[41;0m                    \033[0m" . PHP_EOL
            ],
            [20, 2, \Shelly\Decorator\ShellDecoratorBlock::ALIGN_CENTER, null, 'normal', 'red',
                'test',
                "\033[41;0m                    \033[0m" . PHP_EOL.
                "\033[41;0m        test        \033[0m" . PHP_EOL.
                "\033[41;0m                    \033[0m" . PHP_EOL
            ],
            [25, 5, \Shelly\Decorator\ShellDecoratorBlock::ALIGN_RIGHT, false, 'normal', 'red',
                'Test text its just the test text and we`re all going to die.',
                "\033[41;0m                         \033[0m" . PHP_EOL.
                "\033[41;0m       Test text its     \033[0m" . PHP_EOL.
                "\033[41;0m       just the test     \033[0m" . PHP_EOL.
                "\033[41;0m      text and we`re     \033[0m" . PHP_EOL.
                "\033[41;0m        all going to     \033[0m" . PHP_EOL.
                "\033[41;0m                die.     \033[0m" . PHP_EOL.
                "\033[41;0m                         \033[0m" . PHP_EOL
            ],
        ];
    }


    /**
     * @dataProvider initBadParamsDataProvider
     */
    public function testInitBadParams($w, $m, $a, $bold, $fg, $bg)
    {
        $this->setExpectedException('Exception', \Shelly\Decorator\ShellDecoratorBlock::MSG_INVALID_OPTION_VALUE);
        $d = new \Shelly\Decorator\ShellDecoratorBlock(new \Shelly\ColorShellInterface(new \Shelly\Palette()), [
            'width' => $w,
            'margin' => $m,
            'align' => $a,
            'fg' => $fg,
            'bg' => $bg,
            'bold' => $bold
        ]);
    }

    public function initBadParamsDataProvider()
    {
        return [
            ['auto', 'notint', \Shelly\Decorator\ShellDecoratorBlock::ALIGN_LEFT, true, 'normal', 'red'
            ],
            [30, 1, 33, true, 'blue', 'yellow'
            ],
            [20, 0, \Shelly\Decorator\ShellDecoratorBlock::ALIGN_LEFT, 'sd', 'normal', 'red'
            ],
            [20, 2, \Shelly\Decorator\ShellDecoratorBlock::ALIGN_CENTER, null, '23', 'red'
            ],
            [25, 5, \Shelly\Decorator\ShellDecoratorBlock::ALIGN_RIGHT, false, 'normal', 'necolor'
            ],
        ];
    }


    public function testAutoColumns()
    {
        $siStub = $this->getMockBuilder('\Shelly\ColorShellInterface')
            ->setConstructorArgs([new \Shelly\Palette()])
            ->setMethods(['getCols'])
            ->getMock();
        $siStub->method('getCols')->will($this->returnValue('20'));



        $d = new \Shelly\Decorator\ShellDecoratorBlock($siStub, [
            'width' => 'auto',
            'fg' => 'blue'
        ]);

        $this->assertEquals(
            "\033[34m                    \033[0m" . PHP_EOL .
            "\033[34m  test              \033[0m" . PHP_EOL .
            "\033[34m                    \033[0m" . PHP_EOL
            , $d->decorate('test'));

    }
}
