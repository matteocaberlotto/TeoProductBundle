<?php

namespace Teo\ProductBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
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
        $label = 'Browse file and upload';

        if (is_object($view->vars['value'])) {

            // FIXME:
            $path = $view->vars['value']->getPathname();
            $marker = '/uploads/';
            $path = substr($path, strpos($path, $marker));

            if (preg_match('/(gif|jpeg|png|jpg)$/i', $path)) {
                // that's an image, show preview.
                $view->vars['is_image'] = true;
                $label = sprintf('<img style="width:200px;display:inline-block" src="%s" />', $path . '?rand=' . md5(mt_rand()) . md5(mt_rand()));
                $view->vars['image_info'] = getimagesize($view->vars['value']->getPathname());
            } else {
                // that's a file, show filename.
                $view->vars['is_image'] = false;
                $label = '<i class="icon icon-file"></i> ' . substr(basename($path), 0, 12) . '...';
            }

            $view->vars['rel_path'] = $path;

            $this->vars['file_info'] = array(
                'size' => filesize($view->vars['value']->getPathname())
            );
        }

        // if i spend 1 minute more it's not worth having a form framework.
        foreach ($options['references'] as $image) {
            if (isset($path) && $image->getPath() == $path) {
                $view->vars['reference_id'] = $image->getId();
            }
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
            'object_id' => null,
            'references' => array()
        ));
    }

    public function getName()
    {
        return 'uploadable_file';
    }
}
