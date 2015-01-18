<?php

namespace Teo\ProductBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ColorpickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'empty_data' => null,
            'help' => null
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'colorpicker';
    }
}
