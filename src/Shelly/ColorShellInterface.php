<?php
namespace Shelly\Interaction;

use Shelly\Interaction\Decorator\AbstractDecorator;

/**
 * Class ColorShellInterface
 * @package greezeek\Shelly
 */
class ColorShellInterface extends ShellInterface
{

    const MSG_DECORATOR_NO_NAME = 'Decorator should have a name';
    const MSG_DECORATOR_ARRAY_EXPECTED = 'Array should be passed: [$name, $type, $params]';

    /**
     * @property bool $colorEnabled
     */
    protected $colorEnabled = true;

    /**
     * @var AbstractDecorator[] $decorators
     */
    protected $decorators = [];

    /**
     * @param array[] $decorators
     * @throws \Exception
     * @todo: overload params
     */
    public function __construct(array $decorators = [])
    {

        foreach ($decorators as $decorator) {
            if (!is_array($decorator)) {
                throw new \Exception(self::MSG_DECORATOR_ARRAY_EXPECTED);
            }
            if (!isset($decorator['name']) || !strlen(($name = trim($decorator['name'])))) {
                throw new \Exception(self::MSG_DECORATOR_NO_NAME);
            }

            if (!isset($decorator['type']) || !is_string($type = $decorator['type'])) {
                $type = '';
            }

            if (!isset($decorator['params']) || !is_array($params = $decorator['params'])) {
                $params = [];
            }

            $this->addDecorator($name, $type, $params);
        }

    }


    /**
     * @param $name
     * @param string $type
     * @param array $options
     * @todo: overload params
     */
    public function addDecorator($name, $type = '', array $options = ['bg' => false])
    {
        $decoratorClass = 'Shell\Interaction\Decorator\ShellDecorator' . ucfirst($type);

        if (isset($this->decorators[$name])) {
            unset ($this->decorators[$name]);
        }

        $this->decorators[$name] = new $decoratorClass($this, $options);
    }

    /**
     * @param $name
     * @param $val
     */
    public function decorate($name, $val)
    {
        $this->write($this->decorators[$name]->decorate($val));
    }

    public function decorateRead($name, $hellomessage = ' ? ') {
        $this->decorate($name, $hellomessage);
        return $this->read();
    }


    /**
     *
     */
    public function disableColour()
    {
        $this->colorEnabled = false;
    }


    /**
     *
     */
    public function enableColour()
    {
        $this->colorEnabled = true;
    }


    /**
     * @return bool
     */
    public function isColorEnabled()
    {
        return $this->colorEnabled;
    }
}
