<?php

namespace App\Form;

use App\Entity\Sponsor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class Sponsor1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_sponsor'  ,TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3,
                        'max' => 15,
                        'minMessage' => 'Votre nom du sponsor doit comporter au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre nom du sponsor ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('description_sponsor' , TextareaType::class, [
                'label' => 'Description du sponsor',
                'required' => false,
                'attr' => [
                    'rows' => 5,
                    'cols' => 50
                ],
            ])
            ->add('logo_sponsor' ,
            FileType::class,
            [
                'label' => 'logo',
                'mapped' => false,
                'required' => false,
            ]
        ) 
            ->add('site_webs'  , TextType::class, [
                'constraints' => [
                    new Url([
                        'message' => "L'URL n'est pas valide.",
                    ]),
                ],
            ])
            ->add('id_equipe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sponsor::class,
        ]);
    }
}
