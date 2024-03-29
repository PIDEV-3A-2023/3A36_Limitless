<?php

namespace App\Form;

use App\Entity\Equipe;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\File;
class Equipe1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom_equipe' ,TextType::class, [
            'required' => false,
            'constraints' => [
                
                new Length([
                    'min' => 3,
                    'max' => 15,
                    'minMessage' => 'Votre nom d equipe doit comporter au moins {{ limit }} caractères',
                    'maxMessage' => 'Votre nom d equipe ne peut pas dépasser {{ limit }} caractères',
                ]),
            ],
        ])

        ->add('description_equipe', TextareaType::class, [
            'label' => 'Description de l\'équipe',
            'required' => false,
            'attr' => [
                'rows' => 5,
                'cols' => 50
            ],
        ])
        
        ->add('nb_joueurs' , null, [
            'required' => false,
            'constraints' => [
                new GreaterThanOrEqual([
                    'value' => 1,
                    'message' => 'Le nombre de joueurs doit être supérieur à {{ compared_value }}'
                ]),
                new LessThanOrEqual([
                    'value' => 6,
                    'message' => 'Le nombre de joueurs ne peut pas dépasser {{ compared_value }}',
                ]),
            ],
        ])
        ->add('logo_equipe' ,
        FileType::class,
        [
            'label' => 'logo',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                
                new File([
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Le logo doit être une image JPEG ou PNG',
                ]),
            ],
        ]
    ) 
        ->add('site_web', TextType::class, [
            'required' => false,
            'constraints' => [
                new Url([
                    'message' => "L'URL n'est pas valide.",
                ]),
            ],
        ])
      ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
