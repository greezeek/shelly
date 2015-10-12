<?php
namespace Shelly;

/**
 * Class ShellInterface
 * @package greezeek\Shelly
 */
class ShellInterface implements IshellInterface
{

    const STRING_REQUIRED = 'String required';
    /**
     * @inheritdoc
     */
    public function read()
    {
        $stdin = fopen('php://stdin', 'r');
        $response = fgets($stdin);
        fclose($stdin);

        return rtrim($response, "\n");
    }

    /**
     * @inheritdoc
     */
    public function write($line)
    {
        if(!is_string($line)){
            throw new \Exception('String required');
        }
        print $line;
    }


    /**
     * Tries to get number of cols in current terminal window, or fallbacks to 80
     *
     * @return int
     */
    public function getCols()
    {
        return (in_array(trim(exec('echo $TERM')), ['', 'dumb'])) ? 80 : (int)(@`tput cols`);
    }

    /**
     * Tries to get number of rows in current terminal window, or fallbacks to 40
     *
     * @return int
     */
    public function getRows()
    {
        return (in_array(trim(exec('echo $TERM')), ['', 'dumb'])) ? 40 : (int)(@`tput lines`);
    }


}