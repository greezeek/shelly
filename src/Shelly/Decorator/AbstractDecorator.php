<?php

namespace Shelly\Interaction\Decorator;

use Shelly\Interaction\ColorShellInterface;

abstract class AbstractDecorator
{

    protected $decorator;
    protected $fg;
    protected $bg;

    public static $colors = array(
        'black' => 30,
        'red' => 31,
        'green' => 32,
        'yellow' => 33,
        'blue' => 34,
        'magenta' => 35,
        'cyan' => 36,
        'white' => 37,
        'normal' => '0'
    );

    public static $bgColors = array(
        'black' => 40,
        'red' => 41,
        'green' => 42,
        'yellow' => 43,
        'blue' => 44,
        'magenta' => 45,
        'cyan' => 46,
        'white' => 47,

    );

    public function __construct(ColorShellInterface $decorator, $options)
    {
        $this->decorator = $decorator;
        $this->fg = (isset($options['fg']) && is_string($options['fg']) && array_key_exists($options['fg'],
                self::$colors)) ? $options['fg'] : 'normal';
        $this->bg = (isset($options['bg']) && is_string($options['bg']) && array_key_exists($options['bg'],
                self::$bgColors)) ? $options['bg'] : false;
        $this->bold = isset($options['bold']);
    }

    public function getColor()
    {
        return self::sGetColor($this->fg, $this->bg, $this->bold);
    }

    public static function sGetColor($fg = 'normal', $bg = false, $bold = false)
    {
        $return = '';
        if ($bold) {
            $return .= '1;';
        }
        if ($bg) {
            $return .= self::$bgColors[$bg] . ';';
        }
        $return .= self::$colors[$fg];
        return $return;
    }

    public static function printColourStamp($values)
    {
        return "\033[{$values}m";
    }

    public function decorate($val)
    {
        return
            ($this->decorator->isColorEnabled() ? self::printColourStamp($this->getColor()) : '')
            . $val
            . ($this->decorator->isColorEnabled() ? self::printColourStamp(self::$colors['normal']) : '')
            . ($this->getEol() ? PHP_EOL : '');
    }


    public function __toString()
    {
        return $this->getColor();
    }
}