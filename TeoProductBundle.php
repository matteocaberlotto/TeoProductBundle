<?php

namespace Teo\ProductBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Teo\ProductBundle\Mapping\DoctrineOrmMappingPass;

class TeoProductBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // setup doctrine xml mapping
        $mappings = array(
            realpath(__DIR__ . '/Resources/config/doctrine-model') => 'Teo\ProductBundle\Model'
        );

        $container->addCompilerPass(DoctrineOrmMappingPass::createMappingFor($mappings, 'teo_product.manager_name'));

        // setup own form type views
        $container->loadFromExtension('twig', array(
            'form' => array(
                'resources' => array(
                    'TeoProductBundle:Form:fields.html.twig',
                ),
            ),
        ));
    }
}