<?php

namespace MineDoc\HomeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use MineDoc\HomeBundle\Form\Type\PictureType;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array  $options)
    {
        $builder->add('name')
            ->add('content', 'textarea');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'news';
    }
}