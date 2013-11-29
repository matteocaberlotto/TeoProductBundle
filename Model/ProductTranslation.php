<?php

namespace Teo\ProductBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use A2lix\I18nDoctrineBundle\Doctrine\Interfaces\OneLocaleInterface;

class ProductTranslation implements OneLocaleInterface
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $price;

    protected $product;

    /**
     * Set title
     *
     * @param string $title
     * @return ProductTranslation
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

    /**
     * Set description
     *
     * @param string $description
     * @return ProductTranslation
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

    /**
     * Set price
     *
     * @param string $price
     * @return ProductTranslation
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setProduct($product)
    {
        $this->product = $product;
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
}