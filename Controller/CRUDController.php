<?php

namespace Teo\ProductBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as BaseCRUDController;

class CRUDController extends BaseCRUDController
{
    /**
     * return the Response object associated to the list action
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     *
     * @return Response
     */
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $request = $this->get('request');
        $lastPageViewCache = 'sonata.admin.last_page.' . $this->modelNameLabel();


        if ($this->get('session')->get($lastPageViewCache) && !$request->get('filter')) {
            $filter = $request->get('filter');

            if (!is_array($filter)) {
                $filter = array('_page' => null);
            }

            if ($filter['_page'] !== $this->get('session')->get($lastPageViewCache)) {
                $filter['_page'] = $this->get('session')->get($lastPageViewCache);
                $request->query->set('filter', $filter);
            }
        }

        if ($request->get('filter')) {
            $filter = $request->get('filter');

            if (isset($filter['_page'])) {
                $this->get('session')->set($lastPageViewCache, $filter['_page']);
            }
        }

        $datagrid = $this->admin->getDatagrid();
        $form = $datagrid->getForm();


        $formView = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action'     => 'list',
            'form'       => $formView,
            'datagrid'   => $datagrid,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
        ));
    }

    protected function modelNameLabel()
    {
        $model = $this->admin->getClass();

        $model = strtolower($model);
        $model = str_replace('/[^a-zA-Z0-9]+/', '_', $model);
        $model = str_replace('/_[_]+/', '_', $model);

        return $model;
    }
}