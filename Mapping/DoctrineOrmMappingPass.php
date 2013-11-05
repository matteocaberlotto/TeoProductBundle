<?php

namespace Teo\ProductBundle\Mapping;

use Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterMappingsPass;
use Symfony\Component\DependencyInjection\Definition;

class DoctrineOrmMappingPass extends RegisterMappingsPass {

    /**
     * Creates a new instance of mapping based on folder config and activation flag.
     */
    public static function createMappingFor($mappings, $flag) {

        $arguments = array($mappings, '.orm.xml');
        $locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', $arguments);
        $driver = new Definition('Doctrine\ORM\Mapping\Driver\XmlDriver', array($locator));

        return new DoctrineOrmMappingPass(
            $driver,
            $mappings,
            array($flag, 'doctrine.default_entity_manager'),
            'doctrine.orm.%s_metadata_driver'
        );
    }
}