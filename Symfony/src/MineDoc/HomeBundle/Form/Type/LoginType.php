<?php

namespace MineDoc\HomeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('login', 'text')
                ->add('password', 'password', array('label' => 'Pass'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'login';
    }
}