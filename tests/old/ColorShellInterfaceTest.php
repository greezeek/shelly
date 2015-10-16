<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

class ColorShellInterfaceTest extends PHPUnit_Framework_TestCase
{
    public function testColorEnabled()
    {
        $i = new \Shelly\ColorShellInterface(new \Shelly\Palette());

        $i->enableColour();
        $this->assertTrue($i->isColorEnabled());

        $i->disableColour();
        $this->assertFalse($i->isColorEnabled());

    }

    public function testBadAddDecorator()
    {
        $i = new \Shelly\ColorShellInterface(new \Shelly\Palette());
        $this->setExpectedException('Exception', \Shelly\ColorShellInterface::MSG_DECORATOR_NOT_EXISTS);
        $i->decorate('ne', '123');
    }

    public function testInitDecoratorNoName()
    {
        $this->setExpectedException('Exception', \Shelly\ColorShellInterface::MSG_DECORATOR_NO_NAME);
        $i = new \Shelly\ColorShellInterface(new \Shelly\Palette(),[[]]);
    }

    public function testAddDecoratorNoName()
    {
        $this->setExpectedException('Exception', \Shelly\ColorShellInterface::MSG_DECORATOR_NO_NAME);
        $i = new \Shelly\ColorShellInterface(new \Shelly\Palette());
        $i->addDecorator('');
    }

    public function testAddDecoratorNotExists()
    {
        $this->setExpectedException('Exception', \Shelly\ColorShellInterface::MSG_DECORATOR_CLASS_NOT_EXISTS);
        $i = new \Shelly\ColorShellInterface(new \Shelly\Palette());
        $i->addDecorator('one', 'nex');
    }

    /**
     * @dataProvider decoratorsProvider
     */
    public function testGetDecorator($name, $type, $options, $exp){
        $i = new \Shelly\ColorShellInterface(new \Shelly\Palette());
        $i->addDecorator($name, $type, $options);
        $this->assertInstanceOf($exp, $i->getDecorator($name));
    }


    public function testGetNotExistedDecorator()
    {
        $i = new \Shelly\ColorShellInterface(new \Shelly\Palette());
        $this->setExpectedException('Exception', \Shelly\ColorShellInterface::MSG_DECORATOR_NOT_EXISTS);
        $i->getDecorator('neDecorator');
    }


    public function testResetDecorator()
    {
        $i = new \Shelly\ColorShellInterface(new \Shelly\Palette());
        $i->addDecorator('one', '', []);
        $i->addDecorator('one', 'block', []);
        $this->assertInstanceOf('Shelly\\Decorator\\ShellDecoratorBlock', $i->getDecorator('one'));
    }

    public function decoratorsProvider()
    {
        return [
            ['one', '', [], 'Shelly\\Decorator\\ShellDecorator'],
            ['two', 'block', [], 'Shelly\\Decorator\\ShellDecoratorBlock'],
            ['three', 'template', [], 'Shelly\\Decorator\\ShellDecoratorTemplate']
        ];
    }
}