<?php

namespace Shelly\Decorator;
use Shelly\ColorShellInterface;

class ShellDecorator extends AbstractDecorator {
    protected  $eol;

    public function __construct (ColorShellInterface $decorator, $options) {
        parent::__construct($decorator, $options);
        $this->eol = isset($options['eol']) ? $options['eol'] : false;
    }

    public function getEol() {
        return $this->eol;
    }

    public function decorate($val) {
        return
            ($this->decorator->isColorEnabled()?  self::printColourStamp($this->getColor()) : '')
            . $val
            . ($this->decorator->isColorEnabled()?   self::printColourStamp(self::$colors['normal']) : '')
            . ($this->getEol()?PHP_EOL:'');
    }
}