<?php
/**
 * Created by IntelliJ IDEA.
 * User: swarm
 * Date: 14.10.15
 * Time: 10:59
 */

namespace Shelly;


class Palette
{
    const MSG_GOLOR_NOT_EXISTS = 'Color does not exists';
    /**
     * @var array
     * contains fg colors and it`s aliases
     */
    protected static $colors = [
        'black' => 30,
        'red' => 31,
        'green' => 32,
        'yellow' => 33,
        'blue' => 34,
        'magenta' => 35,
        'cyan' => 36,
        'white' => 37,
        'normal' => 0
    ];

    /**
     * @var array
     * contains bg colors and it`s aliases
     */
    protected static $bgColors = array(
        'black' => 40,
        'red' => 41,
        'green' => 42,
        'yellow' => 43,
        'blue' => 44,
        'magenta' => 45,
        'cyan' => 46,
        'white' => 47,
    );

    /**
     * @param $alias
     * @return string
     */
    public function getColorByAlias($alias) {
        if(array_key_exists($alias, self::$colors)) {
            return self::$colors[$alias];
        }
        return false;
    }

    /**
     * @param $alias
     * @return string
     */
    public function getBgColorByAlias($alias) {
        if(array_key_exists($alias, self::$bgColors)) {
            return self::$bgColors[$alias];
        }
        return false;
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
    public function printColourStamp($fg = 'normal', $bg = null, $bold = null)
    {

        if(!empty($bg) && (false === ($bg = $this->getBgColorByAlias($bg)))) {
            throw new \Exception(self::MSG_GOLOR_NOT_EXISTS);
        }

        if(false === ($fg = $this->getColorByAlias($fg))) {
            throw new \Exception(self::MSG_GOLOR_NOT_EXISTS);
        }

        $return = '';
        if (!empty($bold) && (bool)$bold) {
            $return .= '1;';
        }
        if ($bg) {
            $return .= $bg . ';';
        }
        $return .= $fg;
        return "\033[{$return}m";
    }

}