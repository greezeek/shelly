<?php
/**
 * Created by IntelliJ IDEA.
 * User: swarm
 * Date: 14.10.15
 * Time: 10:53
 */

namespace Shelly\Decorator;


abstract class AbstractSingleColorDecorator extends AbstractDecorator
{
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
                    return false !== $this->getPalette()->getColorByAlias($val);
                }
            ],
            'bg' => [
                'default' => false,
                'validate' => function($val){
                    return empty($val) || $this->getPalette()->getBgColorByAlias($val);
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
     * return color code for unix shell, based on current class options.
     *
     * @return string
     * @throws \Exception
     */
    public function getColor()
    {
        return $this->palette->printColourStamp($this->getOption('fg'), $this->getOption('bg'), $this->getOption('bold'));
    }

    /**
     * return default color code for unix shell
     *
     * @return string
     * @throws \Exception
     */
    public function resetColor()
    {
        return $this->palette->printColourStamp();
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
            ($this->decorator->isColorEnabled() ? $this->getColor() : '')
            . $val
            . ($this->decorator->isColorEnabled() ? $this->resetColor() : '');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getColor();
    }

}