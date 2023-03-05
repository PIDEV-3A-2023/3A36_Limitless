<?php

namespace App\Form;

use App\Entity\Matches;
use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MatchesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tourActuel',ChoiceType::class, [
                'choices'  => [
                    "Finale" => "Finale",
                    "Demi-Finale" => "Demi-Finale",
                    "Quart de Finale" => "Quart de Finale",
                    "Phase de Groupes" => "Phase de Groupes"
                ]
            ])
            ->add('scoreEquipeA')
            ->add('scoreEquipeB')
            ->add('idTournoi')
            ->add('equipe1'/*, EntityType::class, [
                'class' => Equipe::class,
                'constraints' => [
                    new NotEqualTo([
                        'propertyPath' => 'equipe2',
                        'message' => 'The home team and away team must be different.',
                    ]),
                ],
            ]*/)
            ->add('equipe2'/*, EntityType::class, [
                'class' => Equipe::class,
                'constraints' => [
                    new NotEqualTo([
                        'propertyPath' => 'equipe1',
                        'message' => 'The home team and away team must be different.',
                    ]),
                ],
            ]*/);
    }

    

   /* public function validateFields($data, ExecutionContextInterface $context)
    {
        if ($data->getNomEquipe1() === $data->getNomEquipe2()) {
            $context->buildViolation('The two fields cannot have the same value.')
                ->atPath('equipe2')
                ->addViolation();
        }
    }*/

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matches::class,
        ]);
    }
}
