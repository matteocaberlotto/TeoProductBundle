<?php

namespace Teo\ProductBundle\Model;

class Category
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
     * @var \Teo\ProductBundle\Model\Category
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $products;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $categories;

    public function __toString()
    {
        return (string) $this->title;
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
     * Set name
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set parent
     *
     * @param \Teo\ProductBundle\Model\Category $parent
     * @return Category
     */
    public function setParent(\Teo\ProductBundle\Model\Category $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Teo\ProductBundle\Model\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add products
     *
     * @param \Teo\ProductBundle\Model\Product $products
     * @return Category
     */
    public function addProduct(\Teo\ProductBundle\Model\Product $products)
    {
        $this->products[] = $products;
    
        return $this;
    }

    /**
     * Remove products
     *
     * @param \Teo\ProductBundle\Model\Product $products
     */
    public function removeProduct(\Teo\ProductBundle\Model\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set products property
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }


    /**
     * Add categories
     *
     * @param \Teo\ProductBundle\Model\Category $categories
     * @return Category
     */
    public function addCategories(\Teo\ProductBundle\Model\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Teo\ProductBundle\Model\Category $categories
     */
    public function removeCategories(\Teo\ProductBundle\Model\Category $categories)
    {
        $this->categories->removeElement($categories);
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

    public function hasChildren()
    {
        return count($this->categories);
    }

    public function hasProducts()
    {
        return count($this->products);
    }
}