<?php

namespace Teo\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Teo\ProductBundle\Form\DataTransformer\TagsToStringTransformer;
use Sonata\AdminBundle\Route\RouteCollection;
use Teo\ProductBundle\SlugGenerator;


class CategoryAdmin extends Admin
{
    protected $unique_category, $category_extra_options;

    public function setUniqueCategory()
    {
        $this->unique_category = true;
    }

    public function setExtraOptions($options)
    {
        $this->category_extra_options = $options;
    }

    protected function provideOptionsKeys()
    {
        $extra = array();
        foreach ($this->category_extra_options as $name => $opt) {
            $extra []= array(
                $name, $opt['type'], $opt['options']
            );
        }
        return $extra;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $tagsTransformer = new TagsToStringTransformer($this->getModelManager());

        $formMapper
            ->add('title')
            ->add('parent')
            ->add('slug', null, array(
                'required' => false
            ))
            ->add('position', null, array(
                'required' => false
            ))
            ->add('options', 'sonata_type_immutable_array', array(
                    'keys' => $this->provideOptionsKeys(),
                    'required' => false,
                    'data' => $this->getSubject()->getOptions(),
                    'attr' => array(
                        'class' => 'product_extras'
                    )
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
            case 'edit':
                return 'TeoProductBundle:Admin:edit_product.html.twig';
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

    public function prePersist($category)
    {
        $this->updateSlug($category);
    }

    public function preUpdate($category)
    {
        $this->updateSlug($category);
    }

    public function updateSlug($category)
    {
        $slug = $category->getSlug();

        if (empty($slug)) {

            $title = $category->getTitle();

            $inc = 0;
            $collision = array(true);

            while (count($collision)) {
                $slug = SlugGenerator::generate($title, $inc);

                $collision = $this->getModelManager()->findOneBy('TeoProductBundle:Category', array(
                    'slug' => $slug
                ));

                $inc++;
            }

            $category->setSlug($slug);
        }
    }
}