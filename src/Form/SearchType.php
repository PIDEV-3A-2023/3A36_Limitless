<?php

namespace App\Form;

use App\Entity\Jeux;
use App\Entity\CategorieJeux;
use App\Entity\TypeJeux;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('libelle', TextType::class, [
            'label' => 'Libelle de jeux',
            'required' => false,
        ])
        ->add('categories', EntityType::class, [
            'label' => 'Categories',
            'class' => CategorieJeux::class,
            'choice_label' => 'NomCat',
            'required' => false,
            'multiple' => true,
        ])
        ->add('types', EntityType::class, [
            'label' => 'Types',
            'class' => TypeJeux::class,
            'choice_label' => 'NomType',
            'required' => false,
            'multiple' => true,
        ])
        ->add('search', SubmitType::class, [
            'label' => 'Rechercher',
        ])
    ;
}

public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults([
        'method' => 'GET',
    ]);
}
}
    