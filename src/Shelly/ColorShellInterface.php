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
     * @var Palette
     */
    protected $palette;
    /**
     * @property bool $colorEnabled
     */
    protected $colorEnabled = true;

    /**
     * @var AbstractDecorator[] $decorators
     */
    protected $decorators = [];

    /**
     * @param Palette $palette
     * @param array[] $decorators
     * @throws \Exception
     */
    public function __construct(Palette $palette, array $decorators = [])
    {
        $this->setPalette($palette);

        foreach ($decorators as $decorator) {

            if (!array_key_exists('name', $decorator) || empty($decorator['name'])) {
                throw new \Exception(self::MSG_DECORATOR_NO_NAME);
            }

            $type = array_key_exists('type', $decorator) ? $decorator['type'] : null;
            $params = array_key_exists('params', $decorator) ? $decorator['params'] : [];

            $this->addDecorator($decorator['name'], $type, $params);
        }

    }

    /**
     * @param Palette $palette
     */
    public function setPalette(Palette $palette)
    {
        $this->palette = $palette;
    }

    /**
     * @return Palette
     */
    public function getPalette()
    {
        return $this->palette;
    }


    /**
     * @param string $name
     * @param string|AbstractDecorator $type
     * @param array $options
     * @return $this
     * @throws \Exception
     */
    public function addDecorator($name, $type = '', array $options = [])
    {
        if (empty($name)) {
            throw new \Exception(self::MSG_DECORATOR_NO_NAME);
        }

        if($type instanceof AbstractDecorator) {
            $this->decorators[$name] = $type;
        } else {

            $decoratorClass = 'Shelly\Decorator\ShellDecorator' . ucfirst($type);

            if (isset($this->decorators[$name])) {
                unset ($this->decorators[$name]);
            }

            if (!class_exists($decoratorClass)) {
                throw new \Exception(self::MSG_DECORATOR_CLASS_NOT_EXISTS);
            }

            $this->decorators[$name] = new $decoratorClass($this, $options);
        }
        return $this;
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
