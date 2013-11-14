<?php

namespace Teo\ProductBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Teo\ProductBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;

class TagsToStringTransformer implements DataTransformerInterface
{

    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct($om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an array collection (tags) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($tags)
    {
        if (null === $tags) {
            return "";
        }

        if (is_string($tags)) {
            return $tags;
        }

        return implode(', ', $tags->toArray());
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
    public function reverseTransform($tags)
    {
        if (!$tags) {
            return null;
        }

        $tagsCollection = new ArrayCollection();

        foreach (explode(',', $tags) as $tag) {

            $cleanTag = trim($tag);

            $tagObject = $this->om
                ->findOneBy('TeoProductBundle:Tag', array('name' => $cleanTag))
            ;

            if (null === $tagObject) {
                $tagObject = new Tag;
                $tagObject->setName($cleanTag);
                $this->om->getEntityManager('TeoProductBundle:Tag')->persist($tagObject);
            }

            $tagsCollection->add($tagObject);
        }

        return $tagsCollection;
    }
}