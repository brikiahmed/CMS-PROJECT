<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\SubCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subCategories', EntityType::class, [
                'class' => SubCategory::class,
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
            ])
            ->add('title', TextType::class,[
                'constraints' => [
                    new NotBlank()]
            ])
            ->add('category_picture',
                FileType::class, ['mapped' => false, 'data' => $options['image']])
            ->add('short_description', TextType::class,[
                'constraints' => [
                    new NotBlank()]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
            'image' => null,
        ]);
    }
}
