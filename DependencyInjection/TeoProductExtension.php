<?php

namespace Teo\ProductBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
// use Teo\ProductBundle\DependencyInjection\Compiler\ConfigCompiler;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class TeoProductExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if ($config['use_admin']) {
            $loader->load('admin.xml');
        }

        $container->setParameter('teo_product.show_products', $config['show_products']);

        $container->setParameter('teo_product.maximum_depth', $config['maximum_depth']);
        $container->getDefinition('sonata.admin.product')->addMethodCall('setMaximumDepth', array($config['maximum_depth']));

        if ($config['unique_category']) {
            $container->getDefinition('sonata.admin.category')->addMethodCall('setUniqueCategory');
            $container->getDefinition('sonata.admin.product')->addMethodCall('setUniqueCategory');
        }

        if ($config['leaf_only']) {
            $container->getDefinition('sonata.admin.product')->addMethodCall('setLeafOnly');
        }

        if ($config['add_attachment']) {
            $container->getDefinition('sonata.admin.product')->addMethodCall('setAttachment');
        }

        if ($config['use_available']) {
            $container->getDefinition('sonata.admin.product')->addMethodCall('setUseAvailable');
            $container->getDefinition('sonata.admin.category')->addMethodCall('setUseAvailable');
            $container->getDefinition('teo.products')->addMethodCall('setUseAvailable');
            $container->getDefinition('teo.categories')->addMethodCall('setUseAvailable');
        }

        if ($config['use_variant']) {
            $container->getDefinition('sonata.admin.product')->addMethodCall('setUseVariant');
        }

        $container->getDefinition('sonata.admin.category')->addMethodCall('setExtraOptions', array($config['category_extra_options']));
        $container->getDefinition('sonata.admin.product')->addMethodCall('setExtraOptions', array($config['product_extra_options']));
        $container->getDefinition('sonata.admin.product')->addMethodCall('setTranslatableFieldsConfig', array($config['translatable_fields_config']));
    }
}
