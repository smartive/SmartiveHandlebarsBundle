<?php

namespace Smartive\HandlebarsBundle\Tests\Functional\Twig;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HandlebarsExtensionTest extends KernelTestCase
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
        /** @var $twig EngineInterface */
        $twig = $this->container->get('templating');
        $this->assertEquals("Test value\n\n", $twig->render('::test.html.twig', array('key' => 'value')));
    }

    /**
     * @expectedException \Twig_Error_Runtime
     * @expectedExceptionMessage Template notexisting not found.
     */
    public function testTemplateNotFound()
    {
        /** @var $twig EngineInterface */
        $twig = $this->container->get('templating');
        $twig->render('::missing_hbs.html.twig');
    }
}
