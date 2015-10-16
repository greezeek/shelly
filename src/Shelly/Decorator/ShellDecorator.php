<?php

namespace Shelly\Decorator;
use Shelly\ColorShellInterface;

class ShellDecorator extends AbstractSingleColorDecorator {

    public function initDefaultOptions()
    {
        return array_merge(
            parent::initDefaultOptions(),
            [
                'eol' => [
                    'default' => false,
                    'set' => function($val) {return (bool) $val; }
                ]
            ]
        );
    }

    public function decorate($val) {
        return
            parent::decorate($val)
            . ($this->getOption('eol')?PHP_EOL:'');
    }
}