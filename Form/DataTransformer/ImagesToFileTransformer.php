<?php

namespace Teo\ProductBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Teo\ProductBundle\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;

class ImagesToFileTransformer implements DataTransformerInterface
{

    public function __construct($modelManager, $uploadManager, $product)
    {
        $this->modelManager = $modelManager;
        $this->uploadManager = $uploadManager;
        $this->product = $product;
    }

    /**
     * Transforms an array collection (tags) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($images)
    {
        if (null === $images) {
            return array();
        }

        if (is_array($images)) {
            return $images;
        }

        $return = array();
        foreach ($images as $image) {
            $return []= new File($this->uploadManager->getUploadRootDir() . "/" . $image->getPath());
        }

        return $return;
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     *
     * @return Issue|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($images)
    {
        if (!$images) {
            return null;
        }

        $imagesCollection = new ArrayCollection();

        $position = 0;
        foreach ($images as $image) {

            $position++;
            if (is_null($image)) {
                continue;
            }

            $imageObject = null;

            if ($this->product->getId()) {
                $imageObject = $this->modelManager
                    ->findOneBy('TeoProductBundle:Image', array('position' => $position, 'product' => $this->product))
                ;
            }

            if (null === $imageObject) {
                $imageObject = new Image;
                $imageObject->setProduct($this->product);
                $this->modelManager->getEntityManager($imageObject)->persist($imageObject);
                $imagesCollection->add($imageObject);
            }

            $imageObject->setPosition($position);
            $this->uploadManager->upload($imageObject, $image);
        }

        return $imagesCollection;
    }
}