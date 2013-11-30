<?php

namespace Teo\ProductBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class UploadableFileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $label = 'image';

        if (is_object($view->vars['value'])) {
            // FIXME:
            $path = $view->vars['value']->getPathname();
            $marker = '/uploads/';
            $path = substr($path, strpos($path, $marker));
            $label = sprintf('<img src="%s" />', $path . '?rand=' . md5(mt_rand()) . md5(mt_rand()));
            $view->vars['rel_path'] = $path;
            $view->vars['image_info'] = getimagesize($view->vars['value']->getPathname());
        }

        $view->vars = array_replace($view->vars, array(
            'type'  => 'file',
            'label' => $label
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view
            ->vars['multipart'] = true
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => false,
            'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'empty_data' => null,
            'object_id' => null
        ));
    }

    public function getName()
    {
        return 'uploadable_file';
    }
}