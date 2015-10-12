<?php
namespace Shelly;

/**
 * Class ColorShellInterfaceLegacy
 * @package greezeek\Shelly
 */
class ColorShellInterfaceLegacy extends ColorShellInterface
{
    /**
     * @param $line
     * @param string $colour
     */
    public function line($line, $colour = 'normal')
    {
        $this->noeol($line . PHP_EOL, $colour);
    }

    /**
     * @param $line
     * @param string $colour
     */
    public function noeol($line, $colour = 'normal')
    {
        $this->addDecorator($colour, '', ['fg' => $colour]);
        $this->decorate($colour, $line);
    }


    /**
     * @param string $hellomessage
     * @param string $colour
     * @return string
     */
    public function ask($hellomessage = '?', $colour = 'normal')
    {
        if (!is_string($hellomessage)) {
            $hellomessage = '';
        }

        $this->noeol($hellomessage . ' $ ', $colour);

        return static::read();
    }

}
