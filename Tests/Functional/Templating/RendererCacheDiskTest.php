<?php

namespace Smartive\HandlebarsBundle\Tests\Functional\Templating;

use Handlebars\Helper;
use Smartive\HandlebarsBundle\Templating\Renderer;
use Smartive\HandlebarsBundle\Tests\Functional\Templating\HandlebarsHelper\HelloWorldTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RendererCacheDiskTest extends RendererTest
{
    public function setUp($environment = 'test_cache_disk')
    {
        parent::setUp($environment);
    }
}
