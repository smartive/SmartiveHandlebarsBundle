<?php

namespace Smartive\HandlebarsBundle\Tests\Unit\Templating;

use Smartive\HandlebarsBundle\Templating\Renderer;
use Smartive\HandlebarsBundle\Tests\Functional\Templating\HandlebarsHelper\HelloWorldTestHelper;

class RendererTest extends \PHPUnit_Framework_TestCase
{
    private $fileLocator;

    public function setUp()
    {
        $this->fileLocator = $this->getMockBuilder('Symfony\Component\HttpKernel\Config\FileLocator')->disableOriginalConstructor()->getMock();
    }

    public function testRender()
    {
        $renderer = $this->getTestRenderer(false);
        try {
            $renderer->render('test_sub', array('key' => 'value2'));
            $this->fail('Template in sub directory should not have been found.');
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('Template test_sub not found.', $e->getMessage());
        }
    }

    public function testRenderRecursive()
    {
        $renderer = $this->getTestRenderer(true);
        $this->assertSame("Test value\n", $renderer->render('test', array('key' => 'value')));
        $this->assertSame("Test value2 in sub directory\n", $renderer->render('test_sub', array('key' => 'value2')));
    }

    public function testRendererWithHelper()
    {
        $renderer = $this->getTestRenderer(false);
        $renderer->addHelper('helloWorld', new HelloWorldTestHelper());
        $this->assertSame("Hello World", $renderer->render('hello-world'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No Handlebars template directories got defined in "smartive_handlebars.templating.template_directories".
     */
    public function testRendererNoDirectories()
    {
        new Renderer($this->fileLocator, 'hbs', array());
    }

    /**
     * Returns a test renderer which uses the handlebars fixtures
     *
     * @return Renderer
     */
    private function getTestRenderer($recursive)
    {
        $hbsDir = __DIR__ . '/../../Functional/Fixtures/app/Resources/hbs_views/';
        $this->fileLocator->expects($this->once())
            ->method('locate')
            ->with($hbsDir)
            ->will($this->returnValue($hbsDir));
        return new Renderer($this->fileLocator, 'hbs', array($hbsDir), $recursive);
    }
}
