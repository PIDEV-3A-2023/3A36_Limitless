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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Doctrine\ORM\EntityManagerInterface;

class SearchType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du jeux',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'entrer le nom du jeux'
                ]
            ])
            ->add('cat', ChoiceType::class, [
                'label' => 'Categorie',
                'choices' => $this->getCategories(),
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Recherche',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
    }

    private function getCategories()   
    {      
        $query = $this->entityManager->createQuery('SELECT c.NomCat FROM App\Entity\CategorieJeux c');
        $categories = $query->getArrayResult();
        $choices = [];

        foreach ($categories as $category) {
            $choices[$category['NomCat']] = $category['NomCat'];
        }

        return $choices;
    }
}