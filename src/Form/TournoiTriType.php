<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TournoiTriType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('tri', ChoiceType::class, array(
            'choices'  => array(
                'Ordre alphabétique' => 'alpha',
                'Date' => 'date',
                'Type' => 'type',
            ),
            'expanded' => false,
            'multiple' => false,
        ))
        // ->add('submit', SubmitType::class, array('label' => 'Trier'))
        ;
    }
}
