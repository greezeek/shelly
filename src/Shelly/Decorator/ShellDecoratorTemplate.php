<?php

namespace Shelly\Decorator;

use Shelly\ColorShellInterface;


class ShellDecoratorTemplate extends AbstractDecorator
{

    protected $vars = [];
    protected $markup = '';


    public function __construct(ColorShellInterface $decorator, $options)
    {
        $this->decorator = $decorator;
        $this->markup = !empty($options['markup']) ? $options['markup'] : '';
    }


    public function getreplacement($val)
    {

        $ret = '';
        if ($this->decorator->isColorEnabled()) {
            if (isset($val['color']) && $val['color']) {
                $ret .= self::printColourStamp(self::$colors[$val['color']]);
            }
        }
        $ret .= $this->vars[$val['var']];
        if ($this->decorator->isColorEnabled()) {
            if (isset($val['color']) && $val['color']) {
                $ret .= self::printColourStamp(self::$colors['normal']);
            }
        }


        return $ret;

    }

    public function decorate($val)
    {
        $this->vars = $val;
        $markup = $this->markup;
        foreach ($val as $v) {
            $markup = preg_replace_callback("#\<((?<bgcolor>[\w]+?):(?<color>[\w]+?):)?(?<var>[\w]+?)\>#is", 'self::getreplacement', $this->markup);
        }

        return $markup;
    }


}