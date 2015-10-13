<?php

namespace Shelly\Decorator;

use Shelly\ColorShellInterface;

abstract class AbstractDecorator
{

    const MSG_COLOR_NOT_EXISTS = 'Color does not exists';
    const MSG_UNKNOWN_OPTION = 'Unknow option';
    const MSG_INVALID_OPTION_VALUE = 'Invalid option value';
    /**
     * @var ColorShellInterface
     */
    protected $decorator;

    /**
     * @var array
     * contains fg colors and it`s aliases
     */
    public static $colors = [
        'black' => 30,
        'red' => 31,
        'green' => 32,
        'yellow' => 33,
        'blue' => 34,
        'magenta' => 35,
        'cyan' => 36,
        'white' => 37,
        'normal' => '0'
    ];

    /**
     * @var array
     * contains bg colors and it`s aliases
     */
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


    protected $options = [];
    protected $optionsConfig = [];

    /**
     * @param ColorShellInterface $decorator
     * @param array $options
     */
    public function __construct(ColorShellInterface $decorator, array $options)
    {
        $this->decorator = $decorator;
        $this->loadOptions($options, static::initDefaultOptions());
    }

    /**
     * Function should return array of options, that are required by class implementation.
     *
     * @return array
     */
    protected function initDefaultOptions(){
        return [
            'fg' => [
                'default' => 'normal',
                'validate' => function($val){
                    return array_key_exists($val, static::$colors);
                }
            ],
            'bg' => [
                'default' => false,
                'validate' => function($val){
                    return empty($val) || array_key_exists($val, static::$bgColors);
                }
            ],
            'bold' => [
                'default' => false,
                'validate' => function($val){
                    return empty($val) || is_bool($val);},
                'set' => function($val) {return (bool) $val; }
            ],
        ];

    }

    /**
     * Load and validate options from constructor
     *
     * @param array $options
     * @param array $defaultOptions
     * @throws \Exception
     */
    protected function loadOptions(array $options = [], array $defaultOptions)
    {
        $this->optionsConfig = $defaultOptions;

        foreach ($this->optionsConfig as $opt => $data ) {
            $this->options[$opt] = $data['default'];
        }

        foreach ($options as $opt => $val) {
            $this->setOption($opt, $val);
        }
    }

    /**
     * @param string $option
     * @return mixed
     * @throws \Exception
     */
    protected function getOption($option)
    {
        if(!array_key_exists($option, $this->options)) {
            throw new \Exception(self::MSG_UNKNOWN_OPTION);
        }
        return $this->options[$option];
    }

    /**
     * @param string $option
     * @param mixed $value
     * @throws \Exception
     */
    protected function setOption($option, $value)
    {
        if(!isset($this->optionsConfig[$option])) {
            throw new \Exception(self::MSG_UNKNOWN_OPTION);
        }

        if(array_key_exists('validate', $this->optionsConfig[$option]) && !$this->optionsConfig[$option]['validate']($value)) {
            throw new \Exception(self::MSG_INVALID_OPTION_VALUE);
        }

        if(array_key_exists('set', $this->optionsConfig[$option])) {
            $this->options[$option] = $this->optionsConfig[$option]['set']($value);
        } else {
            $this->options[$option] = $value;
        }
    }

    /**
     * return color code for unix shell, based on current class options.
     *
     * @return string
     * @throws \Exception
     */
    public function getColor()
    {
        return self::sGetColor($this->getOption('fg'), $this->getOption('bg'), $this->getOption('bold'));
    }

    /**
     *  return color code for unix shell, based on passed arguments.
     *
     * @param string $fg
     * @param string $bg
     * @param string $bold
     * @return string
     * @throws \Exception
     */
    public static function sGetColor($fg = 'normal', $bg = null, $bold = null)
    {

        if (!empty($fg) && !array_key_exists($fg, self::$colors)) {
            throw new \Exception(self::MSG_COLOR_NOT_EXISTS);
        }

        if (!empty($bg) && !array_key_exists($bg, self::$bgColors)) {
            throw new \Exception(self::MSG_COLOR_NOT_EXISTS);
        }

        $return = '';
        if (!empty($bold) && (bool)$bold) {
            $return .= '1;';
        }
        if ($bg) {
            $return .= self::$bgColors[$bg] . ';';
        }
        $return .= self::$colors[$fg];
        return $return;
    }

    /**
     * Return color sequence with passed color code
     *
     * @param string $values
     * @return string
     */
    public static function printColourStamp($values)
    {
        return "\033[{$values}m";
    }

    /**
     * Return text $val, parsed with current decorator
     *
     * @param $val
     * @return string
     */
    public function decorate($val)
    {
        return
            ($this->decorator->isColorEnabled() ? self::printColourStamp($this->getColor()) : '')
            . $val
            . ($this->decorator->isColorEnabled() ? self::printColourStamp(self::$colors['normal']) : '');
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getColor();
    }
}