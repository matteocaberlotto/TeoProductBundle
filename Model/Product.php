<?php

namespace Teo\ProductBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

class Product
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $prices;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $images;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $categories;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $updated;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getTitle();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Product
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
     * Set slug
     *
     * @param string $slug
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
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
     * Add prices
     *
     * @param \Teo\ProductBundle\Model\Price $prices
     * @return Product
     */
    public function addPrice(\Teo\ProductBundle\Model\Price $prices)
    {
        $this->prices[] = $prices;
    
        return $this;
    }

    /**
     * Remove prices
     *
     * @param \Teo\ProductBundle\Model\Price $prices
     */
    public function removePrice(\Teo\ProductBundle\Model\Price $prices)
    {
        $this->prices->removeElement($prices);
    }

    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * Add images
     *
     * @param \Teo\ProductBundle\Model\Image $images
     * @return Product
     */
    public function addImage(\Teo\ProductBundle\Model\Image $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \Teo\ProductBundle\Model\Image $images
     */
    public function removeImage(\Teo\ProductBundle\Model\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set images
     *
     * @return Product
     */
    public function setImages($images)
    {
        $this->images = $images;
    
        return $this;
    }

    /**
     * Add categories
     *
     * @param \Teo\ProductBundle\Model\Category $categories
     * @return Product
     */
    public function addCategory(\Teo\ProductBundle\Model\Category $category)
    {
        $this->categories[] = $category;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Teo\ProductBundle\Model\Category $categories
     */
    public function removeCategory(\Teo\ProductBundle\Model\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Get categories
     *
     * @return Product
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Reservation
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Reservation
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created value on creation.
     * This is meant to be hooked to lifetime cycle callback.
     */
    public function setCreatedAtValue()
    {
        $this->created = new \DateTime;
    }


    /**
     * Set updated value on update.
     * This is meant to be hooked to lifetime cycle callback.
     */
    public function setUpdatedAtValue()
    {
        $this->updated = new \DateTime;
    }

    public function cleanUpCategories()
    {
        $this->categories = new ArrayCollection;
    }

    public function hasPreview()
    {
        return count($this->images);
    }

    public function getPreview()
    {
        return $this->images[0];
    }
}