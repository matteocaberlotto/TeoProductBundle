<?php

namespace Teo\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Teo\ProductBundle\Form\DataTransformer\TagsToStringTransformer;
use Sonata\AdminBundle\Route\RouteCollection;


class CategoryAdmin extends Admin
{
    protected $unique_category;

    public function setUniqueCategory()
    {
        $this->unique_category = true;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $tagsTransformer = new TagsToStringTransformer($this->getModelManager());

        $formMapper
            ->add('title')
            ->add('parent')
            ->add('position', null, array(
                'required' => false
            ))
            ->add(
                $formMapper->create('tags', 'text', array(
                    'data' => $this->getSubject()->getTags(),
                    'data_class' => null,
                    'label' => 'TAGS:',
                    'required' => false
                ))->addModelTransformer($tagsTransformer)
            )
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('parent')
            ->add('products')
        ;
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'list':
                return 'TeoProductBundle:Admin:categories_listing.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('reorder');
    }
}