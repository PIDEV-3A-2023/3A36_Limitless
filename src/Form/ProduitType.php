<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\CategorieProduit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\File;
class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,['constraints'=>new NotBlank(['message'=>'Peut pas etre vide'])])
            ->add('quantite', IntegerType::class, [
            'constraints' => [
                new GreaterThan([
                'value' => 0,
                'message' => 'Valeur négative non autorisée'
        ])
    ]
])
           ->add('prix', TextType::class, [
            'constraints' => [
                new GreaterThan([
                'value' => 0,
                'message' => 'Valeur négative non autorisée'
        ])
    ]
])
            ->add('image', FileType::class, [
    'constraints' => [new NotBlank(['message'=>'Vous devez insérer une image']),
        new File([
            'maxSize' => '1024k',
            'mimeTypes' => [
                'image/jpeg',
                'image/png',
            ],
            'mimeTypesMessage' => 'Please upload a valid image (JPEG or PNG).',
            'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). La taille maximum doit-être {{ limit }} {{ suffix }}.'
        ])
    ]
])
            ->add('categorieProduit',EntityType::class,['class'=>CategorieProduit::class,'choice_label'=>'nom',])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
