<?php

namespace Smartive\HandlebarsBundle\Tests\Functional\Templating;

use Handlebars\Helper;
use Smartive\HandlebarsBundle\Templating\Renderer;
use Smartive\HandlebarsBundle\Tests\Functional\Templating\HandlebarsHelper\HelloWorldTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RendererCacheApcTest extends RendererTest
{
    public function setUp($environment = 'test_cache_apc')
    {
        if (!function_exists('apc_fetch')) {
            $this->markTestSkipped('APC not available, skipping test.');
        }
        parent::setUp($environment);
    }
}
