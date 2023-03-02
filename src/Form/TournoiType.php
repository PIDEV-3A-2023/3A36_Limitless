<?php

namespace App\Form;

use App\Entity\Tournoi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityManagerInterface;
class TournoiType extends AbstractType
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
    //dump($options);
        $builder
            ->add('nomTournoi')
            ->add('jeu')
            ->add('participantTotal',ChoiceType::class, [
                'choices'  => [
                    8 => 8,
                    16 => 16,
                    32 => 32,
                ]
            ])
            ->add('nomHote')
            ->add('dateDebut', DateTimeType::class)
            ->add('prix')
            ->add('typeTournoi',ChoiceType::class, [
                'choices' => [
                    "1v1" => "1v1",
                    "2v2" => "2v2",
                    "4v4" => "4v4",
                ]/*$this->getTypeJeu($options['jeu'])*/,
            ])
            
            ->add('imageTournoi', FileType::class, array('data_class' => null),
            ['required' => false])
                
            ;
    }
    
    private function getTypeJeu($nom)   /*on ne recoit pas l'attribut nom*/
    {      
          $query = $this->entityManager->createQuery('SELECT t FROM App\Entity\Type t JOIN t.jeus j WHERE j.nomJeu = :nomJeu')
           ->setParameter('nomJeu',$nom);
           $categories = $query->getArrayResult();
            $choices = [];
    
            foreach ($categories as $category) {
                $choices[$category['typejeu']] = $category['typejeu'];
            }
    
            return $choices;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournoi::class, 'jeu' => null,
        ]);
    }

}
