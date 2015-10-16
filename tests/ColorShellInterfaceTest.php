<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

class ColorShellInterfaceTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers ColorShellInterface::__construct
     */
    public function testConstruct()
    {
        $si = new \Shelly\ColorShellInterface(new \Shelly\Palette(), [['name' => 'test', 'type' => 'block']]);

        $this->assertAttributeInstanceOf('Shelly\Palette', 'palette', $si);
        $this->assertAttributeCount(1, 'decorators', $si);

        $property = new ReflectionProperty('\Shelly\ColorShellInterface', 'decorators');
        $property->setAccessible(true);

        $val = $property->getValue($si);

        $this->assertInstanceOf('Shelly\Decorator\ShellDecoratorBlock', $val['test']);

        $si->addDecorator('test', '');
        $si->addDecorator('block', 'block');
        $si->addDecorator('template', 'template');
        $si->addDecorator('instance',
            new \Shelly\Decorator\ShellDecoratorBlock($si, ['bg'=>'red'])
        );

        $this->assertAttributeCount(4, 'decorators', $si);

        $val = $property->getValue($si);

        $this->assertInstanceOf('Shelly\Decorator\ShellDecorator', $val['test']);
        $this->assertInstanceOf('Shelly\Decorator\ShellDecoratorBlock', $val['block']);
        $this->assertInstanceOf('Shelly\Decorator\ShellDecoratorTemplate', $val['template']);
        $this->assertInstanceOf('Shelly\Decorator\ShellDecoratorBlock', $val['instance']);

    }

}