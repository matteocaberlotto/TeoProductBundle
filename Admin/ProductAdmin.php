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
use Teo\ProductBundle\Form\DataTransformer\CategoryToCollectionTransformer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class ProductAdmin extends Admin
{
    protected $um;

    protected $unique_category;

    protected $leaf_only;

    public function setUniqueCategory()
    {
        $this->unique_category = true;
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

        $formMapper
            ->add('title', 'text', array(
                'label' => 'Product title'
            ))
            ->add('description', 'text', array(
                'label' => 'Product description',
                'required' => false
            ))
            ->add(
                $formMapper->create('images', 'collection', array(
                    'data' => $this->getSubject()->getImages(),
                    'data_class' => null,
                    'allow_add' => true,
                    'type' => 'uploadable_file',
                    'label' => 'Product images',
                    'required' => false
                ))->addModelTransformer($imagesToFileTransformer)
            )
        ;

        if ($this->leaf_only) {
            $depth = $this->maximum_depth;
            $categoriesFieldConfig['query_builder'] = function (EntityRepository $er) use ($depth) {
                return $er->createQueryBuilder('c')
                    ->where('c.level = :level')
                    ->setParameter('level', $depth)
                    ;
            };
        }

        // $type = null;
        if ($this->unique_category) {

            $category = $this->getSubject()->getCategories();

            $formMapper->add(
                $formMapper->create('categories', 'entity', array(
                    'class' => 'Teo\ProductBundle\Entity\Category',
                    'property' => 'title',
                    'data' => $category->first(),
                    'required' => false
                ))->addModelTransformer(new CategoryToCollectionTransformer)
            );
        } else {
            $formMapper->add('categories', null, array(
                'required' => false
            ));
        }
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('description')
            ->add('categories')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('images', null, array(
                'label' => 'Preview',
                'template' => 'TeoProductBundle:Admin:product_categories_preview.html.twig'
            ))
            ->addIdentifier('title')
            ->add('description')
            ->add('categories', null, array(
                'template' => 'TeoProductBundle:Admin:product_categories_field.html.twig'
            ))
        ;
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
}