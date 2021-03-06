<?php

namespace Teo\ProductBundle\Model;

use Teo\ProductBundle\Model\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Teo\ProductBundle\Model\Tag;

class CategoryTranslation
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var Teo\ProductBundle\Model\Category
     */
    protected $category;

    /**
     * @var string
     */
    protected $description;

    /**
     * Set title
     *
     * @param string $title
     * @return CategoryTranslation
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Here follows the A2lix bundle trait Translatable
     */
    protected $id;

    /**
     * @var string
     */
    protected $locale;

    public function getId()
    {
        return $this->id;
    }

    public function getTranslatable()
    {
        return $this->translatable;
    }

    public function setTranslatable($translatable)
    {
        $this->translatable = $translatable;
        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CategoryTranslation
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
