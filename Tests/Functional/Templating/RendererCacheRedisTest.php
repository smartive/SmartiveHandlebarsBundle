<?php

namespace Smartive\HandlebarsBundle\Tests\Functional\Templating;

use Handlebars\Helper;
use PHPUnit_Framework_MockObject_MockObject;
use Predis\Client;
use Smartive\HandlebarsBundle\Templating\Renderer;
use Smartive\HandlebarsBundle\Tests\Functional\Templating\HandlebarsHelper\HelloWorldTestHelper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RendererCacheRedisTest extends RendererTest
{
    public function setUp($environment = 'test_cache_redis')
    {
        $redis = null;
        $config = 'tcp://127.0.0.1:6379';
        if (class_exists('\Predis\Client')) {
            $redis = new Client($config);
        } else {
            $this->markTestSkipped(sprintf('The %s requires the predis library.', __CLASS__));
        }
        if (null !== $redis) {
            try {
                $ok = $redis->ping();
            } catch (\Exception $e) {
                $ok = false;
            }
            if (!$ok) {
                $this->markTestSkipped(sprintf('The %s requires a redis instance listening on %s.', __CLASS__, $config));
            }
        }

        parent::setUp($environment);
    }
}
