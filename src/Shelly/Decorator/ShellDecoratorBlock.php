<?php

namespace Shelly\Interaction\Decorator;

use Shelly\Interaction\ColorShellInterface;
use Shelly\String;

class ShellDecoratorBlock extends AbstractDecorator
{
    const PAGE_WIDTH = 'auto';
    const MARGIN = 2;
    const PADDING = 1;
    const ALIGN_LEFT = 'left';
    const ALIGN_RIGHT = 'right';
    const ALIGN_CENTER = 'center';

    protected $margin;
    protected $align;
    protected $width;

    protected $availableAlign = [
        self::ALIGN_LEFT,
        self::ALIGN_RIGHT,
        self::ALIGN_CENTER,
    ];

    public function __construct(ColorShellInterface $decorator, $options)
    {
        parent::__construct($decorator, $options);

        $this->width = isset($options['width']) ? $options['width'] : self::PAGE_WIDTH;

        if ($this->width === 'auto') {
            $this->width = $this->decorator->getCols();
        }

        $this->margin = isset($options['margin']) ? $options['margin'] : self::MARGIN;

        $this->align = (isset($options['align']) && in_array($options['align'],
                $this->availableAlign)) ? $options['align'] : self::ALIGN_LEFT;
    }


    public function decorate($val)
    {

        $width = $this->width;
        $margin = $this->margin;
        $stringLength = $width - ($margin * 2);
        $blank = str_repeat(' ', $this->width);
        $val = String::arrayFixedWidthSaveWords($val, $stringLength);

        $ret =
            ($this->decorator->isColorEnabled() ? self::printColourStamp($this->getColor()) : '')
            . $blank
            . ($this->decorator->isColorEnabled() ? self::printColourStamp(self::$colors['normal']) : '') . PHP_EOL;

        foreach ($val as $v) {

            $marginStr = str_repeat(' ', $margin);
            $freeCells = $width - strlen($v) - ($margin * 2);

            $marginLeft = $marginRight = $marginStr;

            if ($freeCells > 0) {

                if ($this->align == self::ALIGN_LEFT) {
                    $marginRight .= str_repeat(' ', $freeCells);
                } elseif ($this->align == self::ALIGN_CENTER) {

                    $half = round($freeCells / 2);
                    $marginLeft .= str_repeat(' ', $half);
                    $marginRight .= str_repeat(' ', $freeCells - $half);

                } else {
                    $marginLeft .= str_repeat(' ', $freeCells);
                }
            }

            $ret .=
                ($this->decorator->isColorEnabled() ? self::printColourStamp($this->getColor()) : '') .
                $marginLeft . $v . $marginRight
                . ($this->decorator->isColorEnabled() ? self::printColourStamp(self::$colors['normal']) : '') . PHP_EOL;
        }

        $ret .=
            ($this->decorator->isColorEnabled() ? self::printColourStamp($this->getColor()) : '')
            . $blank
            . ($this->decorator->isColorEnabled() ? self::printColourStamp(self::$colors['normal']) : '') . PHP_EOL;

        return $ret;
    }
}