<?php

namespace Teo\ProductBundle\Model;

use Teo\ProductBundle\Model\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Teo\ProductBundle\Model\Tag;

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
     * @var Category
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $products;

    /**
     * @var integer the category nesting depth
     */
    protected $level;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $categories;

    protected $tags;

    public function __construct() {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @param Category $parent
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return Category 
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
     * @param Category $categories
     * @return Category
     */
    public function addCategories(Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param Category $categories
     */
    public function removeCategories(Category $categories)
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

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set level
     *
     * @return Category
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Set level based on depth self-calculation
     *
     * @return Category
     */
    public function setCurrentLevel()
    {
        $this->setLevel($this->calculateLevel());

        return $this;
    }

    public function calculateLevel()
    {
        $level = 1;
        $current = $this;

        while ($current->getParent() instanceof Category) {
            $current = $current->getParent();
            $level++;
        }

        return $level;
    }
    /**
     * Add tags
     *
     * @param \Teo\ProductBundle\Model\Tag $tags
     * @return Bookmark
     */
    public function addTag(\Teo\ProductBundle\Model\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Teo\ProductBundle\Model\Tag $tags
     */
    public function removeTag(\Teo\ProductBundle\Model\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
}