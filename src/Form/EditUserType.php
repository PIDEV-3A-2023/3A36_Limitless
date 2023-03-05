<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
class EditUserType extends AbstractType
{




    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'Le Nom doit etre alphabetique.',
                    ])
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'Le Prenom doit etre alphabetique.',
                    ])
                ],
            ])
            ->add('email', EmailType::class)
            ->add('mdp', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères.',
                    ])
                ],
            ])
            ->add('datenai', DateType::class, [
                'widget' => 'single_text',
                'years' => range(1900, date('Y') + 5),
                'format' => 'yyyy-MM-dd',
                
                'constraints' => [
                    new Assert\LessThanOrEqual([
                        'value' => (new \DateTime())->modify('-16 years'),
                        'message' => 'Vous devez avoir au moins 16 ans pour vous inscrire.'
                ])
                ]
            ])
            ->add('pays', CountryType::class)
            ->add(
                'pprofile',
                FileType::class,
                [
                    'label' => 'Photo de profil',
                    'mapped' => false,
                    'required' => false,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
