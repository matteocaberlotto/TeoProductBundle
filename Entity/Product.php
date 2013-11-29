<?php

namespace Teo\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Teo\ProductBundle\Model\Product as BaseProduct;

/**
 * Product
 */
class Product extends BaseProduct
{

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $translations;

    public static function getTranslationEntityClass()
    {
        return __CLASS__ . 'Translation';
    }
}