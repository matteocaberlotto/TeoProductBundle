<?php

namespace Teo\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Teo\ProductBundle\Model\Category as BaseCategory;

/**
 * Category
 */
class Category extends BaseCategory
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