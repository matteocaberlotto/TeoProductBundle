<?php

namespace Teo\ProductBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductCRUDController extends CRUDController
{
    public function reorderAction()
    {
        $ids = $this->getRequest()->get('ids');
        $this->getDoctrine()->getRepository('TeoProductBundle:Product')->reorder($ids);
        return new JsonResponse(array('result' => true), 200);
    }
}