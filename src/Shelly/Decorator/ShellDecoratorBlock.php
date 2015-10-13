<?php

namespace Shelly\Decorator;

use Shelly\ColorShellInterface;
use Shelly\String;

class ShellDecoratorBlock extends AbstractDecorator
{
    const ALIGN_LEFT = 'left';
    const ALIGN_RIGHT = 'right';
    const ALIGN_CENTER = 'center';

    protected function initDefaultOptions() {
        return array_merge(
            parent::initDefaultOptions(), [
                'width' => [
                    'default' => function(){$this->decorator->getCols();},
                    'validate' => function($val) {return (is_numeric($val) && ((int)$val > 0)) || ($val === 'auto'); },
                    'set' => function($val) {return  ($val === 'auto') ? $this->decorator->getCols() : (int)$val; }
                ],
                'margin' => [
                    'default' => 2,
                    'validate' => function($val) {return is_numeric($val) && ((int)$val >= 0); },
                ],
                'align' => [
                    'default' => self::ALIGN_LEFT,
                    'validate' => function($val) {return in_array($val, [
                        self::ALIGN_LEFT,
                        self::ALIGN_RIGHT,
                        self::ALIGN_CENTER,
                    ], true); },
                ],
            ]
        );
    }

    public function decorate($val)
    {

        $width = $this->getOption('width');
        $margin = $this->getOption('margin');
        $stringLength = $width - ($margin * 2);
        $blank = str_repeat(' ', $this->getOption('width'));
        $val = $this->arrayFixedWidthSaveWords($val, $stringLength);

        $ret =
            ($this->decorator->isColorEnabled() ? self::printColourStamp($this->getColor()) : '')
            . $blank
            . ($this->decorator->isColorEnabled() ? self::printColourStamp(self::$colors['normal']) : '') . PHP_EOL;

        foreach ($val as $v) {

            $marginStr = str_repeat(' ', $margin);
            $freeCells = $width - strlen($v) - ($margin * 2);

            $marginLeft = $marginRight = $marginStr;

            if ($freeCells > 0) {

                if ($this->getOption('align') === self::ALIGN_LEFT) {
                    $marginRight .= str_repeat(' ', $freeCells);
                } elseif ($this->getOption('align') === self::ALIGN_CENTER) {

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


    /**
     * Finds passed $symbol in $string on closest position to $position below.
     *
     * @param string $string
     * @param string $symbol
     * @param int $position
     * @return bool|int
     */
    public function findClosestSymbol($string, $symbol, $position)
    {
        $position = (int)$position;

        if($position <= 1 ) {
            return false;
        }

        if($position > strlen($string)) {
            return strlen($string);
        }

        $temp = substr($string, 0, $position);
        $pos = strripos($temp, $symbol);

        if (!$pos) {
            $pos = $position;
        }

        return $pos;
    }


    /**
     * Breaks $string apart, separating by spaces, each part is shorter then $length
     *
     * @param $string
     * @param $length
     * @return array
     */
    public function arrayFixedWidthSaveWords($string, $length)
    {

        $string = trim($string, ' ');
        $ret = [];

        while (false !== ($pos = self::findClosestSymbol($string, ' ', $length)) && strlen($string) > 0) {
            $ret[] = trim(substr($string, 0, $pos), ' ');
            $string = trim(substr($string, $pos));
        }

        return $ret;
    }


}