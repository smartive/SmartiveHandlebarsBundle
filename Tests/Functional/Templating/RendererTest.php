<?php

namespace Smartive\HandlebarsBundle\Tests\Functional\Templating;

use Smartive\HandlebarsBundle\Templating\Renderer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RendererTest extends KernelTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setUp()
    {
        static::bootKernel();
        $this->container = static::$kernel->getContainer();
    }

    public function testRender()
    {
        /** @var $renderer Renderer */
        $renderer = $this->container->get('smartive_handlebars.templating.renderer');
        $this->assertEquals("Test value\n", $renderer->render('test', array('key' => 'value')));
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
}
