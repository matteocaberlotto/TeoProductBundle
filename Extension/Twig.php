<?php

namespace Teo\ProductBundle\Extension;

class Twig extends \Twig_Extension
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getGlobals()
    {
        return array(

        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('count', array($this, 'count')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_service', array($this, 'getService')),
        );
    }

    public function getService($id)
    {
        return $this->container->get($id);
    }

    public function getName()
    {
        return 'teo_product_extension';
    }
}
