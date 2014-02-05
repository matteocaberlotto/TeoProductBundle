<?php

namespace Teo\ProductBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Teo\ProductBundle\Entity\Attachment;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;

class AttachmentToFileTransformer implements DataTransformerInterface
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
    public function transform($attachments)
    {
        if (null === $attachments) {
            return array();
        }

        if (is_array($attachments)) {
            return $attachments;
        }

        $return = array();
        foreach ($attachments as $attachment) {
            $return []= new File($this->uploadManager->getWebDir() . $attachment->getPath());
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
    public function reverseTransform($attachments)
    {
        if (!$attachments) {
            return null;
        }

        $attachmentsCollection = new ArrayCollection();

        $position = 0;
        foreach ($attachments as $image) {

            if (isset($_POST['delete_images'][$position]) && $_POST['delete_images'][$position] == 'on') {
                // delete file and continue

                $fileObject = $this->modelManager
                    ->findOneBy('TeoProductBundle:Attachment', array('position' => $position, 'product' => $this->product))
                ;
                $this->modelManager->getEntityManager($fileObject)->remove($fileObject);
                $position++;
                continue;
            }

            $fileObject = null;

            if ($this->product->getId()) {
                $fileObject = $this->modelManager
                    ->findOneBy('TeoProductBundle:Attachment', array('position' => $position, 'product' => $this->product))
                ;
            }

            $samePathCheck = false;
            if (!is_null($image) && !is_null($fileObject)) {
                $relPath = substr($image->getPathname(), strpos($image->getPathname(), '/web') + 4);
                if ($relPath == $fileObject->getPath()) {
                    $samePathCheck = true;
                }
            }

            // if there's no content or there's no update to filepath, just update position and continue
            if (is_null($image) || $samePathCheck) {
                if ($fileObject) {
                    $fileObject->setPosition($position);
                    $attachmentsCollection->add($fileObject);
                }
                $position++;
                continue;
            }

            // create and save a new image
            if (null === $fileObject) {
                $fileObject = new Attachment;
                $fileObject->setProduct($this->product);
                $fileObject->setPosition($position);
                $this->modelManager->getEntityManager($fileObject)->persist($fileObject);
            }

            $this->uploadManager->upload($fileObject, $image);

            $attachmentsCollection->add($fileObject);
            $position++;
        }

        // force update positions
        $counter = 0;
        foreach ($attachmentsCollection as $att) {
            $att->setPosition($counter);
            $counter++;
        }

        return $attachmentsCollection;
    }
}