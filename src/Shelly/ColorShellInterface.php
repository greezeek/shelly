<?php
namespace Shelly;

use Shelly\Decorator\AbstractDecorator;

/**
 * Class ColorShellInterface
 * @package greezeek\Shelly
 */
class ColorShellInterface extends ShellInterface
{

    const MSG_DECORATOR_NO_NAME = 'Decorator should have a name';
    const MSG_DECORATOR_CLASS_NOT_EXISTS = 'Decorator class does not exists';
    const MSG_DECORATOR_NOT_EXISTS = 'Decorator does not exists';

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
            if (empty($decorator['name'])) {
                throw new \Exception(self::MSG_DECORATOR_NO_NAME);
            }

            if (!isset($decorator['type']) || !is_string($type = $decorator['type'])) {
                $type = '';
            }

            if (!isset($decorator['params']) || !is_array($params = $decorator['params'])) {
                $params = [];
            }

            $this->addDecorator($decorator['name'], $type, $params);
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
        $decoratorClass = 'Shelly\Decorator\ShellDecorator' . ucfirst($type);

        if (empty($name)) {
            throw new \Exception(self::MSG_DECORATOR_NO_NAME);
        }

        if (isset($this->decorators[$name])) {
            unset ($this->decorators[$name]);
        }

        if (!class_exists($decoratorClass)) {
            throw new \Exception(self::MSG_DECORATOR_CLASS_NOT_EXISTS);
        }

        $this->decorators[$name] = new $decoratorClass($this, $options);
    }


    /**
     * @param $name
     * @param $val
     * @throws \Exception
     */
    public function decorate($name, $val)
    {
        if (!in_array($name, $this->decorators)) {
            throw new \Exception(self::MSG_DECORATOR_NOT_EXISTS);
        }
        $this->write($this->decorators[$name]->decorate($val));
    }

    public function decorateRead($name, $hellomessage = ' ? ')
    {
        $this->decorate($name, $hellomessage);
        return $this->read();
    }

    /**
     * @param string $name
     */
    public function getDecorator($name)
    {
        if(array_key_exists($name, $this->decorators))
            return $this->decorators[$name];
        throw new \Exception(self::MSG_DECORATOR_NOT_EXISTS);
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
