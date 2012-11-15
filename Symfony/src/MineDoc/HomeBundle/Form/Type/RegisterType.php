<?php

namespace MineDoc\HomeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('login', 'text')
            ->add('password', 'password')
            ->add('firstname', 'text')
            ->add('name', 'text')
            ->add('parrain', 'text')
            ->add('mail', 'email');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'register';
    }
}