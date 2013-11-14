<?php

namespace Teo\ProductBundle\Model;

class Image
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var integer
     */
    protected $position;

    /**
     * @var \Teo\ProductBundle\Model\Product
     */
    protected $product;

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
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Image
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
     * Set product
     *
     * @param \Teo\ProductBundle\Model\Product $product
     * @return Image
     */
    public function setProduct(\Teo\ProductBundle\Model\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \Teo\ProductBundle\Model\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}