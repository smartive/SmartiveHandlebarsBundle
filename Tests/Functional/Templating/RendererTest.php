<?php

namespace Smartive\HandlebarsBundle\Tests\Functional\Templating;

use Handlebars\Helper;
use Smartive\HandlebarsBundle\Templating\Renderer;
use Smartive\HandlebarsBundle\Tests\Functional\Templating\HandlebarsHelper\HelloWorldTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RendererTest extends KernelTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setUp($environment = 'test')
    {
        static::bootKernel(['environment' => $environment]);
        $this->container = static::$kernel->getContainer();
    }

    public function testRender()
    {
        /** @var $renderer Renderer */
        $renderer = $this->container->get('smartive_handlebars.templating.renderer');
        $this->assertSame("Test value\n", $renderer->render('test', array('key' => 'value')));
        $this->assertSame("Test value2 in sub directory\n", $renderer->render('test_sub', array('key' => 'value2')));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Template notexisting not found
     */
    public function testTemplateNotFound()
    {
        /** @var $renderer Renderer */
        $renderer = $this->container->get('smartive_handlebars.templating.renderer');
        $renderer->render('notexisting');
    }

    public function testRenderWithHelper()
    {
        /** @var $renderer Renderer */
        $renderer = $this->container->get('smartive_handlebars.templating.renderer');
        $renderer->addHelper('helloWorld', new HelloWorldTestHelper());
        $this->assertSame("Hello World", $renderer->render('hello-world'));
    }
}
