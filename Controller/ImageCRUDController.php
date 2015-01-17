<?php

namespace Teo\ProductBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImageCRUDController extends CRUDController
{
    public function reorderAction()
    {
        $ids = $this->getRequest()->get('ids');
        $this->getDoctrine()->getRepository($this->admin->getClass())->reorder($ids);
        return new JsonResponse(array('result' => true), 200);
    }

    // TODO: refactor this method
    public function cropAction()
    {
        $candidate = $this->getRequest()->server->get('HTTP_REFERER');

        // make sure we get back to product page (fallback to admin home)
        if (strpos($candidate, '/product/product/') !== false) {
            $return_path = $candidate;
            $this->getRequest()->getSession()->set('crop_return_path', $return_path);
        } else {
            $return_path = $this->getRequest()->getSession()->get('crop_return_path');
            if (empty($return_path)) {
                $return_path = '/admin/dashboard';
            }
        }

        $path = $this->getRequest()->get('path');

        $source_path = $this->container->getParameter('kernel.root_dir') . "/../web" . $this->getRequest()->get('src');
        list( $source_width, $source_height, $source_type ) = getimagesize($source_path);

        if ($this->getRequest()->isMethod('post')) {

            $vector = $this->getRequest()->get('v');

            switch ( $source_type )
            {
            case IMAGETYPE_GIF:
                $source_gdim = imagecreatefromgif($source_path);
                break;

            case IMAGETYPE_JPEG:
                $source_gdim = imagecreatefromjpeg($source_path);
                break;

            case IMAGETYPE_PNG:
                $source_gdim = imagecreatefrompng($source_path);
                break;
            }

            $width = $vector[2] - $vector[0];
            $height = $vector[3] - $vector[1];

            // if crop makes sense
            if ($width < $source_width) {
                $dest = imagecreatetruecolor($width, $height);
                imagecopy($dest, $source_gdim, 0, 0, $vector[0], $vector[1], $width, $height);

                switch ( $source_type )
                {
                case IMAGETYPE_GIF:
                    imagegif($dest, $source_path);
                    break;

                case IMAGETYPE_JPEG:
                    imagejpeg($dest, $source_path);
                    break;

                case IMAGETYPE_PNG:
                    imagepng($dest, $source_path);
                    break;
                }

                // use new width for rendering
                list( $source_width, $source_height, $source_type ) = getimagesize($source_path);

                $this->addFlash('sonata_flash_success', 'Image cropped');
            } else {
                $this->addFlash('sonata_flash_error', 'Image not cropped: wrong dimensions');
            }
        }

        return $this->render('TeoProductBundle:Admin:crop_image.html.twig', array(
            'path' => $path,
            'action' => 'crop',
            'actual_width' => $source_width,
            'return_path' => $return_path,
            'random' => md5(mt_rand()) . md5(mt_rand())
        ));
    }

    public function rotateImageAction()
    {
        $direction = $this->getRequest()->get('direction');
        $path = $this->getRequest()->get('path');
        $source_path = $this->container->getParameter('kernel.root_dir') . "/../web" . $this->getRequest()->get('path');
        list( $source_width, $source_height, $source_type ) = getimagesize($source_path);
        switch ( $source_type )
        {
        case IMAGETYPE_GIF:
            $source_gdim = imagecreatefromgif($source_path);
            break;

        case IMAGETYPE_JPEG:
            $source_gdim = imagecreatefromjpeg($source_path);
            break;

        case IMAGETYPE_PNG:
            $source_gdim = imagecreatefrompng($source_path);
            break;
        }

        $degree = -90;
        if ($direction === 'counterclockwise') {
            $degree = 90;
        }

        $rotate = imagerotate($source_gdim, $degree, 0);

        switch ( $source_type )
        {
        case IMAGETYPE_GIF:
            imagegif($rotate, $source_path);
            break;

        case IMAGETYPE_JPEG:
            imagejpeg($rotate, $source_path);
            break;

        case IMAGETYPE_PNG:
            imagepng($rotate, $source_path);
            break;
        }

        return $this->redirect($this->generateUrl(
            'admin_teo_product_image_crop', array(
            'src' => $path
        )));
    }
}