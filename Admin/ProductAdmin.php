<?php

namespace Teo\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Teo\ProductBundle\Form\DataTransformer\ImagesToFileTransformer;
use Teo\ProductBundle\Form\DataTransformer\AttachmentToFileTransformer;
use Teo\ProductBundle\Form\DataTransformer\CategoryToCollectionTransformer;
use Doctrine\ORM\EntityRepository;
use Teo\ProductBundle\SlugGenerator;
use Sonata\AdminBundle\Route\RouteCollection;

class ProductAdmin extends Admin
{
    protected $um;

    protected $unique_category;

    protected $leaf_only = false;

    protected $product_extra_options;

    protected $visible_fields;

    protected $attachment = false;

    protected $use_available = false;

    protected $use_variant = false;

    protected $product_class = false;

    protected $category_class = false;

    protected $transformers = array();

    public function addTrasformer($transformer)
    {
        $this->transformers []= $transformer;
    }

    public function setUniqueCategory()
    {
        $this->unique_category = true;
    }

    public function setLocales($locales)
    {
        $this->locales = $locales;
    }

    public function setupLocales($provider)
    {
        $this->setLocales($provider->languages());
    }

    public function setAttachment()
    {
        $this->attachment = true;
    }

    public function setExtraOptions($options)
    {
        $this->product_extra_options = $options;
    }

    public function setTranslatableFieldsConfig($config)
    {
        $this->fields_config = $config;
    }

    protected function provideOptionsKeys()
    {
        $extra = array();
        foreach ($this->product_extra_options as $name => $opt) {
            $extra []= array(
                $name, $opt['type'], $opt['options']
            );
        }

        return $extra;
    }

    public function setUploadManager($uploadManager)
    {
        $this->um = $uploadManager;
    }

    public function setLeafOnly()
    {
        $this->leaf_only = true;
    }

    public function setMaximumDepth($depth)
    {
        $this->maximum_depth = $depth;
    }

    public function setUseAvailable()
    {
        $this->use_available = true;
    }

    public function setUseVariant()
    {
        $this->use_variant = true;
    }

    public function setCategoryClass($class)
    {
        $this->category_class = $class;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $imagesToFileTransformer = new ImagesToFileTransformer($this->getModelManager(), $this->um, $this->getSubject(), $this->image_class);
        $attachmentToFileTransformer = new AttachmentToFileTransformer($this->getModelManager(), $this->um, $this->getSubject());

        $options = array(
            'required' => false,
            'fields' => $this->fields_config
        );

        if (!empty($this->locales)) {
            $options['locales'] = $this->locales;
        }

        $formMapper->add('translations', 'a2lix_translations', $options);

        if ($this->use_available) {
            $formMapper
                ->add('available')
                ;
        }

        $formMapper
            ->add('position', null, array(
                'required' => false,
                'help' => 'the position of the product'
            ))
            ->add('slug', null, array(
                'required' => false,
                'help' => 'Human readable suffix for urls (leave blank to autogenerate)'
            ))
            ->add('extras', 'sonata_type_immutable_array', array(
                'keys' => $this->provideOptionsKeys(),
                'required' => false,
                'data' => $this->getSubject()->getExtras(),
                'attr' => array(
                    'class' => 'product_extras'
                ),
                'help' => 'extra options'
            ))
        ;

        if ($this->use_variant) {
            $formMapper
                ->add('variants', 'collection', array(
                    'allow_add' => true,
                    'prototype_name' => 'variante',
                    'label' => 'Variante prodotto',
                    'allow_delete' => true
                ))
                ->add('additions', 'collection', array(
                    'allow_add' => true,
                    'prototype_name' => 'variante',
                    'label' => 'Ingredienti addizionabili',
                    'allow_delete' => true
                ))
                ;
        }

        if ($this->leaf_only) {
            $depth = $this->maximum_depth;
            $categoriesFieldConfig['query_builder'] = function (EntityRepository $er) use ($depth) {
                return $er->createQueryBuilder('c')
                    ->where('c.level = :level')
                    ->setParameter('level', $depth)
                    ;
            };
        }

        if ($this->unique_category) {
            $formMapper->add(
                $formMapper->create('categories', 'entity', array(
                    'class' => $this->category_class,
                    'data' => $this->getSubject()->getCategories()->first(),
                    'required' => false,
                    'label' => 'Category'
                ))->addModelTransformer(new CategoryToCollectionTransformer)
            );
        } else {
            $formMapper->add('categories', null, array(
                'required' => false,
                'label' => 'Categories'
            ));
        }

        $formMapper
            ->add(
                $formMapper->create('images', 'sonata_type_native_collection', array(
                    'data' => $this->getSubject()->getImages(),
                    'data_class' => null,
                    'allow_add' => true,
                    'type' => 'uploadable_file',
                    'label' => 'Product images',
                    'required' => false,
                    'attr' => array(
                        'class' => 'teo_product_images'
                    ),
                    'options' => array(
                        'references' => $this->getSubject()->getImages()
                    )
                ))->addModelTransformer($imagesToFileTransformer)
            )
        ;

        if ($this->attachment) {
            $formMapper
                ->add(
                    $formMapper->create('attachments', 'sonata_type_native_collection', array(
                        'data' => $this->getSubject()->getAttachments(),
                        'data_class' => null,
                        'allow_add' => true,
                        'type' => 'uploadable_file',
                        'label' => 'File attachments',
                        'required' => false,
                        'attr' => array(
                            'class' => 'teo_product_attachments'
                        ),
                        'options' => array(
                            'references' => $this->getSubject()->getAttachments()
                        )
                    ))->addModelTransformer($attachmentToFileTransformer)
                )
            ;
        }

        foreach ($this->transformers as $transformer) {
            $transformer->configureFormFields($formMapper);
        }
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('categories')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('edit', null, array(
                'label' => 'Actions',
                'template' => 'TeoProductBundle:Admin:product_edit.html.twig'
                ))
            ->add('images', null, array(
                'label' => 'Preview',
                'template' => 'TeoProductBundle:Admin:product_preview.html.twig'
            ))
            ->addIdentifier('slug')
            ->add('translations', null, array(
                'template' => 'TeoProductBundle:Admin:product_info.html.twig'
            ))
            ->add('categories', null, array(
                'template' => 'TeoProductBundle:Admin:product_categories_field.html.twig'
            ))
        ;

        if ($this->use_variant) {
            $listMapper
                ->add('variants', null, array(
                'template' => 'TeoProductBundle:Admin:product_variants.html.twig'
            ))
                ->add('additions', null, array(
                'template' => 'TeoProductBundle:Admin:product_additions.html.twig'
            ))
                ;
        }
    }

    public function getTemplate($name)
    {
        switch ($name) {
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
        $collection->add('search');
    }

    public function prePersist($product)
    {
        $this->updateSlug($product);
    }

    public function preUpdate($product)
    {
        $this->updateSlug($product);
    }

    public function updateSlug($product)
    {
        $slug = $product->getSlug();

        if (empty($slug)) {

            $title = $product->title();

            $inc = 0;
            $collision = array(true);

            while (count($collision)) {
                $slug = SlugGenerator::generate($title, $inc);

                $collision = $this->getModelManager()->findOneBy($this->getClass(), array(
                    'slug' => $slug
                ));

                $inc++;
            }

            $product->setSlug($slug);
        }
    }
}
