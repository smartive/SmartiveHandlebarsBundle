<?php

namespace Smartive\HandlebarsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('smartive_handlebars');
        $rootNode
            ->children()
                ->arrayNode('templating')
                    ->canBeDisabled()
                    ->children()
                        ->scalarNode('file_extension')
                            ->defaultValue('.hbs')
                        ->end()
                        ->arrayNode('template_directories')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->info('List of Handlebars template directories to include for rendering.')
                            ->prototype('scalar')->end()
                        ->end()
                        ->booleanNode('template_directories_recursive')
                            ->defaultValue(true)
                            ->info('Whether to include the template directory\'s sub directories when looking for Handlebars templates.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('twig')
                    ->canBeDisabled()
                ->end()
                ->arrayNode('cache')
                    ->canBeEnabled()
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function ($v) { return array('enabled' => true, 'service' => $v); })
                    ->end()
                    ->children()
                        ->scalarNode('service')
                            ->isRequired()
                            ->info('Service that implements Handlebars\Cache.')
                        ->end()
                        ->arrayNode('redis')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('client_service')
                                    ->info('Service ID of the Redis client to use - has to be of type \Predis\Client or \Redis.')
                                    ->defaultValue('snc_redis.default')
                                ->end()
                                ->scalarNode('key_prefix')
                                    ->info('The key prefix to use when storing objects in Redis.')
                                    ->defaultValue('smartive-handlebars:')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
