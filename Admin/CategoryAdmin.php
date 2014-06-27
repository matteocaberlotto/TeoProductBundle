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
            ->add('translations', 'a2lix_translations', array(
                'required' => false,
                'fields' => array(
                    'title' => array(
                        'attr' => array(
                            'class' => 'span10',
                            'rows' => 8
                        )
                    ),
                    'description' => array(
                        'attr' => array(
                            'class' => 'span10',
                            'rows' => 8
                        )
                    )
                )
            ));

        $formMapper
            ->add('parent', null, array(
                'property' => 'pathString',
                'help' => 'the parent category'
            ))
            ->add('slug', null, array(
                'required' => false,
                'help' => 'leave blank to auto-generate'
            ))
            ->add('position', null, array(
                'required' => false,
                'help' => 'the position of the category'
            ))
            ->add('options', 'sonata_type_immutable_array', array(
                'keys' => $this->provideOptionsKeys(),
                'required' => false,
                'data' => $this->getSubject()->getOptions(),
                'attr' => array(
                    'class' => 'product_extras'
                ),
                'help' => 'extra options'
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
            ->add('parent')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            // ->add('title')
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

    public function updateTags($category)
    {
        if (count($category->getTags())) {
            foreach ($category->getTags() as $tag) {
                if (!$tag->getId()) {
                    $tag->addCategory($category);
                }
            }
        }
    }

    public function prePersist($category)
    {
        $this->updateSlug($category);
        $this->updateTags($category);
    }

    public function preUpdate($category)
    {
        $this->updateSlug($category);
        $this->updateTags($category);
    }

    public function updateSlug($category)
    {
        $slug = $category->getSlug();

        if (empty($slug)) {

            $title = $category->title();

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