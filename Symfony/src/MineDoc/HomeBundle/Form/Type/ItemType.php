<?php

namespace MineDoc\HomeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name', 'text')
            ->add('gameid', 'text')
            ->add('price', 'text')
            ->add('gamename', 'text')
            ->add('category', 'choice', array(
                'required' => false,
                'empty_value' => 'Choose',
                'choices' => array(
                    'nourriture' => 'Nourriture',
                    'bloc' => 'Bloc',
                    'ressource' => 'Ressource',
                    'utilitaire' => 'Utilitaire',
                    'autre' => 'Autre',
                )))
            ->add('available', 'text');
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'item';
    }
}