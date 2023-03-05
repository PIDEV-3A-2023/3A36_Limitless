<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Doctrine\ORM\EntityManagerInterface;

class TournoiRechercheType extends AbstractType
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom',TextType::class)
        //->add('jeu',TextType::class)
        ->add('jeu',ChoiceType::class, [
            'choices' => $this->getJeu(),
        ])
        ->add('dateFrom',DateType::class, [
                'label' => ' ',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                 'data' => new \DateTime()
                // any additional options you want to set
            ]/*[
            'constraints' => [
                new GreaterThanOrEqual([
                    'value' => 'today',
                    'message' => "La date doit etre superieure à la date d' aujourdhui.",
                ]),
            ],
        ]*/)
        ->add('dateTo',DateType::class, [
            'label' => 'to',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
             'data' => new \DateTime()
            // any additional options you want to set
        ],[
            'constraints' => [
                new GreaterThanOrEqual([
                    'propertyPath' => 'parent.all[dateFrom].data',
                    'message' => 'La date doit etre superieure à la premiere date.',
                ]),
            ],
        ])
       /*->add('tri', ChoiceType::class, array(
            'choices'  => array(
                'Ordre alphabétique' => 'alpha',
                'Date' => 'date',
            ),'data' => 'alpha',
            'expanded' => true,
            'multiple' => false,
        ))*/
        ;
    }
    private function getJeu()   
    {      
          $query = $this->entityManager->createQuery('SELECT j.ref FROM App\Entity\Jeux j');
           $categories = $query->getArrayResult();
            $choices = [];
    
            foreach ($categories as $category) {
                $choices[$category['ref']] = $category['ref'];
            }       
    
            return $choices;
// return $categories;
    }

}
