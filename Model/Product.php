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
    protected $slug;

    /**
     * @var integer
     */
    protected $position;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $images;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $attachments;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $categories;

    /**
     * @var array
     */
    private $extras;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var \DateTime
     */
    protected $updated;

    // not mapped, added by Teo\ProductBundle
    protected $current_locale;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attachments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add attachments
     *
     * @param \Teo\ProductBundle\Model\Attachment $attachments
     * @return Product
     */
    public function addAttachment(\Teo\ProductBundle\Model\Attachment $attachments)
    {
        $this->attachments[] = $attachments;
    
        return $this;
    }

    /**
     * Remove attachments
     *
     * @param \Teo\ProductBundle\Model\Attachment $attachments
     */
    public function removeAttachment(\Teo\ProductBundle\Model\Attachment $attachments)
    {
        $this->attachments->removeElement($attachments);
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

    public function getCategory()
    {
        return $this->categories->first();
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

    /**
     * Set extras
     *
     * @param array $extras
     * @return Product
     */
    public function setExtras($extras)
    {
        $this->extras = $extras;
    
        return $this;
    }

    /**
     * Get extra
     *
     * @return array 
     */
    public function getExtras()
    {
        return $this->extras;
    }

    public function getExtra($key)
    {
        if (isset($this->extras[$key])) {
            return $this->extras[$key];
        }
        return false;
    }

    public function setExtra($key, $value)
    {
        $this->extras[$key] = $value;

        return $this;
    }

    public function hasExtra($key)
    {
        return isset($this->extras[$key]);
    }

    public function hasAttachments() {
        return count($this->attachments);
    }

    public function getAttachments() {
        return $this->attachments;
    }

    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
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