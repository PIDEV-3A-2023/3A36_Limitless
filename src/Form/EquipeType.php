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
class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_equipe' ,TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3,
                        'max' => 15,
                        'minMessage' => 'Votre nom d equipe doit comporter au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre nom d equipe ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('description_equipe')
            ->add('nb_joueurs' , null, [
                'constraints' => [
                    new NotBlank(),
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
            ]
        ) 
            ->add('site_web', TextType::class, [
                'constraints' => [
                    new Url([
                        'message' => "L'URL n'est pas valide.",
                    ]),
                ],
            ])
            ->add('date_creation', DateType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La date de création ne peut pas être vide.',
                    ]),
                    new LessThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de création doit être antérieure ou égale à la date actuelle.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
