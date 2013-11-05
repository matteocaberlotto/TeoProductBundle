<?php

namespace Teo\ProductBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Teo\ProductBundle\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;

class CategoryToCollectionTransformer implements DataTransformerInterface
{
    /**
     * Transforms an array collection (tags) to a string (number).
     *
     * @param  Category|null $issue
     * @return string
     */
    public function transform($categories)
    {
        return $categories;
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
    public function reverseTransform($category)
    {
        if (!$category) {
            return array();
        }

        return array( $category );
    }
}