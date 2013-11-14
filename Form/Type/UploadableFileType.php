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
            $label = $view->vars['value']->getFilename();
            $label = sprintf('<img src="/uploads/documents/%s" /> %s', $label, $label);
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
        ));
    }

    public function getName()
    {
        return 'uploadable_file';
    }
}