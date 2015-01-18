<?php

namespace Teo\ProductBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductCRUDController extends CRUDController
{
    public function reorderAction()
    {
        $ids = $this->getRequest()->get('ids');
        $this->getDoctrine()->getRepository($this->admin->getClass())->reorder($ids);

        return new JsonResponse(array('result' => true), 200);
    }

    public function searchAction(Request $request)
    {
        $search = $request->get('q');
        $products = $this->get('teo.products')->searchProduct($search);

        $results = array();
        array_walk($products, function ($product) use (&$results) {
            $results []= array(
                'id' => $product->getId(),
                'text' => $product->getTitle()
                );
        });

        return new JsonResponse(array(
            'results' => $results
            ));
    }
}
