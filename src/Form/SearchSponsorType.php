<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class SearchSponsorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('query', SearchType::class, [
            'label' => 'Search',
            'attr' => [
                'placeholder' => 'Entrez un ou plusieurs mots-clés',
                'class' => 'form-control'
            ],
            'required' => false,
        ])
        
        //->add('Rechercher', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            
        ]);
    }
}
