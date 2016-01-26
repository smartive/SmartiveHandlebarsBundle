<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Smartive\HandlebarsBundle\SmartiveHandlebarsBundle(),
        );

        if ($this->getEnvironment() === 'test_cache_redis') {
            $bundles[] = new \Snc\RedisBundle\SncRedisBundle();
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_' . $this->getEnvironment() . '.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/smartive-handlebars-bundle/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/smartive-handlebars-bundle/logs';
    }
}
