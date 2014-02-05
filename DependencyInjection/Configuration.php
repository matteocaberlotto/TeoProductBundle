<?php

namespace Teo\ProductBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('teo_product');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->scalarNode('manager_name')
                    ->defaultValue('default')
                ->end()
                ->scalarNode('use_admin')
                    ->defaultValue(false)
                ->end()
                ->scalarNode('show_products')
                    ->defaultValue(false)
                ->end()
                ->scalarNode('unique_category')
                    ->defaultValue(false)
                ->end()
                ->scalarNode('maximum_depth')
                    ->defaultValue(2)
                ->end()
                ->scalarNode('leaf_only')
                    ->defaultValue(false)
                ->end()
                ->scalarNode('add_attachment')
                    ->defaultValue(false)
                ->end()
                ->arrayNode('translatable_fields_config')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('locale_options')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('display')->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('attr')
                                ->children()
                                    ->scalarNode('class')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->defaultValue(array('title' => array(
                    )))
                ->end()
                ->arrayNode('category_extra_options')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('type')->end()
                            ->arrayNode('options')
                                ->children()
                                    ->scalarNode('help')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('product_extra_options')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('type')->end()
                            ->arrayNode('options')
                                ->children()
                                    ->scalarNode('help')->end()
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
