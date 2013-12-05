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
    protected $slug;

    /**
     * @var Category
     */
    protected $parent;

    /**
     * @var integer
     */
    protected $position;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $products;

    /**
     * @var integer the category nesting depth
     */
    protected $level;

    /**
     * @var array category custom extra options
     */
    protected $options;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $categories;

    // not mapped, added by Teo\ProductBundle
    protected $current_locale;

    protected $tags;

    public function __construct() {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getSlug();
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

    public function setCurrentLocale($locale)
    {
        $this->current_locale = $locale;

        return $this;
    }

    public function getCurrentLocale($locale)
    {
        return $this->current_locale;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = $position;
    
        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
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

    public function getOrderedProducts()
    {
        $iterator = $this->products->getIterator();

        $iterator->uasort(function ($first, $second) {
            return (int) $first->getPosition() > (int) $second->getPosition() ? 1 : -1;
        });

        return $iterator;
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

    public function getOrderedCategories()
    {
        $iterator = $this->categories->getIterator();

        $iterator->uasort(function ($first, $second) {
            return (int) $first->getPosition() > (int) $second->getPosition() ? 1 : -1;
        });

        return $iterator;
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

    public function getPath()
    {
        $path = array(ucfirst($this->getSlug()));

        $current = $this;

        while ($current->getParent() instanceof Category) {
            $current = $current->getParent();
            $path []= ucfirst($current->getSlug());
        }

        return array_reverse($path);
    }

    public function getPathString()
    {
        return implode(' > ', $this->getPath());
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

    /**
     * Set tags
     *
     * @return Category
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }


    /**
     * Set options
     *
     * @param array $options
     * @return Category
     */
    public function setOptions($options)
    {
        $this->options = $options;
    
        return $this;
    }

    /**
     * Get options
     *
     * @return array 
     */
    public function getOptions()
    {
        return $this->options;
    }

    public function getOption($key, $default = false)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }

        return $default;
    }

    public function setOption($key, $value)
    {
        $this->option[$key] = $value;

        return $this;
    }

    /**
     * Here follows the A2lix bundle trait Translatable
     */

    public function getTranslations()
    {
        return $this->translations = $this->translations ? : new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setTranslations(\Doctrine\Common\Collections\ArrayCollection $translations)
    {
        $this->translations = $translations;
        return $this;
    }

    public function addTranslation($translation)
    {
        $this->getTranslations()->set($translation->getLocale(), $translation);
        $translation->setTranslatable($this);
        return $this;
    }

    public function removeTranslation($translation)
    {
        $this->getTranslations()->removeElement($translation);
    }

    public function getCurrentTranslation()
    {
        foreach ($this->getTranslations() as $translation) {
            if ($translation->getLocale() == $this->current_locale) {
                return $translation;
            }
        }
        return $this->getTranslations()->first();
    }

    public function __call($method, $args)
    {
        return ($translation = $this->getCurrentTranslation()) ?
                call_user_func(array(
                    $translation,
                    'get' . ucfirst($method)
                )) : '';
    }
}