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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)

            ->add('description',TextareaType::class, [
             'attr' => ['rows' => 5, 'cols' => 30]])

            ->add('quantite', IntegerType::class)

            ->add('prix', IntegerType::class)

            ->add('image', FileType::class,['data_class'=>null])

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


//array('data_class' => null)