<?php

namespace Xi\Bundle\ArticleBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('xi_article');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
        ->children()
                ->arrayNode('acl_roles')
                    ->children()
                        ->arrayNode('edit')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('block')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('introduction')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('article_link')
                                    ->defaultTrue()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('content')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('article_link')
                                    ->defaultFalse()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('parent_layout')
                    ->defaultNull()
                ->end()
                ->scalarNode('admin_parent_layout')
                    ->defaultNull()
                ->end()                    
                ->arrayNode('form')
                    ->children()               
                         ->scalarNode('date_widget')
                            ->defaultValue('single_text')
                        ->end()                
                        ->scalarNode('datepicker_class')
                            ->defaultValue('datepicker')
                        ->end()
                        ->scalarNode('datepicker_format')
                            ->defaultValue('dd.MM.yyyy')
                        ->end()                
                    ->end()
                ->end()                
        ->end();
        
        return $treeBuilder;
    }
}
