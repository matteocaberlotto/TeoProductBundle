<?php

namespace Teo\ProductBundle\Model;

use Teo\ProductBundle\Model\Category;

class Tag
{
    protected $id;

    protected $name;

    protected $categories;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
     * Add categories
     *
     * @param \Teo\ProductBundle\Entity\Category $category
     * @return Tag
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Notbook\CoreBundle\Entity\Bookmark $categories
     */
    public function removeCategory(Category $category)
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
}