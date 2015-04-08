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
                    ->children()
                        ->arrayNode('template_directories')
                            ->prototype('scalar')->end()
                            ->treatNullLike(array())
                            ->info('List of Handlebars template directories to include for rendering.')
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
            ->end()
        ;

        return $treeBuilder;
    }
}
