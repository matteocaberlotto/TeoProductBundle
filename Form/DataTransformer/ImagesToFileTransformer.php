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
            $return []= new File($this->uploadManager->getWebDir() . $image->getPath());
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

            if (isset($_POST['delete_images'][$position]) && $_POST['delete_images'][$position] == 'on') {
                // delete image and continue

                $imageObject = $this->modelManager
                    ->findOneBy('TeoProductBundle:Image', array('position' => $position, 'product' => $this->product))
                ;
                $this->modelManager->getEntityManager($imageObject)->remove($imageObject);
                $position++;
                continue;
            }

            $imageObject = null;

            if ($this->product->getId()) {
                $imageObject = $this->modelManager
                    ->findOneBy('TeoProductBundle:Image', array('position' => $position, 'product' => $this->product))
                ;
            }

            // if there's no content, there's no update to filepath, just update position and continue
            if (is_null($image)) {
                if ($imageObject) {
                    $imageObject->setPosition($position);
                    $imagesCollection->add($imageObject);
                }
                $position++;
                continue;
            }

            if (null === $imageObject) {
                $imageObject = new Image;
                $imageObject->setProduct($this->product);
                $imageObject->setPosition($position);
                $this->modelManager->getEntityManager($imageObject)->persist($imageObject);
            }

            $this->uploadManager->upload($imageObject, $image);

            $imagesCollection->add($imageObject);
            $position++;
        }

        // force update positions
        $counter = 0;
        foreach ($imagesCollection as $img) {
            $img->setPosition($counter);
            $counter++;
        }

        return $imagesCollection;
    }
}