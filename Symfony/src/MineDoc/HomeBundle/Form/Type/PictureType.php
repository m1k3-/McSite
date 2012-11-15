<?php

namespace MineDoc\HomeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PictureType extends AbstractType
{
    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false,
        );
    }

    public function buildForm(FormBuilder $builder, array  $options)
    {
        $builder
            ->add('file');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'picture';
    }
}