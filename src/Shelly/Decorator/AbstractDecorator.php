<?php

namespace Shelly\Decorator;

use Shelly\ColorShellInterface;
use Shelly\Palette;

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
     * @var Palette
     */
    protected $palette;

    protected $options = [];
    protected $optionsConfig = [];

    /**
     * @param ColorShellInterface $decorator
     * @param array $options
     */
    public function __construct(ColorShellInterface $decorator, array $options = [])
    {
        $this->decorator = $decorator;
        $this->setPalette($this->decorator->getPalette());
        $this->loadOptions($options, static::initDefaultOptions());
    }

    /**
     * @param Palette $palette
     */
    public function setPalette(Palette $palette)
    {
        $this->palette = $palette;
    }

    /**
     * @return Palette
     */
    function getPalette()
    {
        return $this->palette;
    }

    /**
     * Function should return array of options, that are required by class implementation.
     *
     * @return array
     */
    protected function initDefaultOptions()
    {
        return [];

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

        foreach ($this->optionsConfig as $opt => $data) {
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
        if (!array_key_exists($option, $this->options)) {
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
        if (!isset($this->optionsConfig[$option])) {
            throw new \Exception(self::MSG_UNKNOWN_OPTION);
        }

        if (array_key_exists('validate', $this->optionsConfig[$option]) && !$this->optionsConfig[$option]['validate']($value)) {
            throw new \Exception(self::MSG_INVALID_OPTION_VALUE);
        }

        if (array_key_exists('set', $this->optionsConfig[$option])) {
            $this->options[$option] = $this->optionsConfig[$option]['set']($value);
        } else {
            $this->options[$option] = $value;
        }
    }


    /**
     * Return text $val, parsed with current decorator
     *
     * @param $val
     * @return string
     */
    abstract public function decorate($val);


}