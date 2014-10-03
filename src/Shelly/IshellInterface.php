<?php
namespace Shelly\Interaction;

/**
 * Interface IshellInterface
 * @package greezeek\Shelly
 */
interface IshellInterface
{
    /**
     * Capture stdin until endo of line passed and return captured string
     *
     * @return string <p>captured string</p>
     */
    public function read();

    /**
     *  Send text to stdout
     *
     * @param string $line <p>Text to output</p>
     * @return void
     */
    public function write($line);
}