<?php

namespace Teo\ProductBundle\Model;

class Price
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $amount;

    /**
     * @var \Teo\ProductBundle\Model\Country
     */
    protected $country;

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
     * Set amount
     *
     * @param string $amount
     * @return Price
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set country
     *
     * @param \Teo\ProductBundle\Model\Country $country
     * @return Price
     */
    public function setCountry(\Teo\ProductBundle\Model\Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return \Teo\ProductBundle\Model\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set product
     *
     * @param \Teo\ProductBundle\Model\Product $product
     * @return Price
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