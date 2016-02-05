<?php

namespace Smartive\HandlebarsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SmartiveHandlebarsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $serviceNames = [
            'templating',
            'twig',
            'cache'
        ];

        foreach ($serviceNames as $serviceName) {
            if ($this->isConfigEnabled($container, $config[$serviceName])) {
                $this->loadService($serviceName, $config[$serviceName], $container, $loader);

                if ('cache' === $serviceName) {
                    $this->loadCache($container, $config);
                }
            }
        }
    }

    /**
     * Loads the service with the given name and configuration
     *
     * @param string           $serviceName Name of the service
     * @param array            $config      Configuration values
     * @param ContainerBuilder $container   Container instance
     * @param XmlFileLoader    $loader      Loader instance
     *
     * @return void
     */
    private function loadService($serviceName, array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $configFile = $serviceName . '.xml';

        $this->setConfigurationAsParameters($serviceName, $config, $container);

        $loader->load($configFile);
    }

    /**
     * Sets all parameter values for the given service and configuration
     *
     * @param string           $serviceName Name of the service
     * @param array            $config      Configuration values
     * @param ContainerBuilder $container   Container instance
     *
     * @return void
     */
    private function setConfigurationAsParameters($serviceName, array $config, ContainerBuilder $container)
    {
        foreach ($config as $configName => $configValue) {
            $container->setParameter($this->getParameterName($serviceName, $configName), $configValue);
        }
    }

    /**
     * Returns the parameter name for a given service and configuration entry
     *
     * @param string $serviceName Name of the service
     * @param string $configName  Configuration entry name
     *
     * @return string
     */
    private function getParameterName($serviceName, $configName)
    {
        return sprintf('%s.%s.%s', $this->getAlias(), $serviceName, $configName);
    }

    /**
     * Loads the caching service
     *
     * @param ContainerBuilder $container Container instance
     * @param array            $config    Configuration values
     *
     * @return void
     */
    private function loadCache(ContainerBuilder $container, array $config)
    {
        if (empty($config['cache']['service'])) {
            throw new InvalidConfigurationException('You need to specify a cache service in order to enable caching.');
        }

        try {
            $templatingService = $container->findDefinition('smartive_handlebars.templating.renderer');
        } catch (InvalidArgumentException $e) {
            throw new InvalidConfigurationException('Caching can only be configured if templating is enabled.');
        }

        $this->prepareRedisCache($container, $config['cache']);

        $templatingService->addMethodCall('setCache', [new Reference($config['cache']['service'])]);
    }

    /**
     * Prepares the Redis cache
     *
     * @param ContainerBuilder $container   Container instance
     * @param array            $cacheConfig Cache configuration values
     *
     * @return void
     */
    private function prepareRedisCache(ContainerBuilder $container, array $cacheConfig)
    {
        $redisCacheService = $container->findDefinition('smartive_handlebars.cache.redis');
        $redisCacheService->replaceArgument(0, new Reference($cacheConfig['redis']['client_service']));
        $redisCacheService->replaceArgument(2, $cacheConfig['redis']['key_prefix']);
    }
}
