<?php

namespace Teo\ProductBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Teo\ProductBundle\Form\UploadHelper;
use Teo\ProductBundle\Form\DataTransformer\ImagesToFileTransformer;
use Teo\ProductBundle\Form\DataTransformer\AttachmentToFileTransformer;
use Teo\ProductBundle\Form\DataTransformer\CategoryToCollectionTransformer;
use Doctrine\Common\Collections\ArrayCollection;
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

    protected $use_price = false;

    public function setUniqueCategory()
    {
        $this->unique_category = true;
    }

    public function setUsePrice()
    {
        $this->use_price = true;
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

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $imagesToFileTransformer = new ImagesToFileTransformer($this->getModelManager(), $this->um, $this->getSubject());
        $attachmentToFileTransformer = new AttachmentToFileTransformer($this->getModelManager(), $this->um, $this->getSubject());

        $formMapper->add('translations', 'a2lix_translations', array(
            'required' => false,
            'fields' => $this->fields_config
        ));

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

        if ($this->use_price) {
            $formMapper->add('price', 'money');
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

            $category = $this->getSubject()->getCategories();

            $formMapper->add(
                $formMapper->create('categories', 'entity', array(
                    'class' => 'Teo\ProductBundle\Entity\Category',
                    'property' => 'pathString',
                    'data' => $category->first(),
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
                $formMapper->create('images', 'collection', array(
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
                    $formMapper->create('attachments', 'collection', array(
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

        if ($this->use_price) {
            $listMapper->add('price');
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

                $collision = $this->getModelManager()->findOneBy('TeoProductBundle:Product', array(
                    'slug' => $slug
                ));

                $inc++;
            }

            $product->setSlug($slug);
        }
    }
}