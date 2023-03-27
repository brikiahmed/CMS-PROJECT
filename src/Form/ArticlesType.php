<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Fournisseur;
use App\Entity\SubCategory;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'constraints' => [
                    new NotBlank()]
            ])
            ->add('url', TextType::class,[
                'constraints' => [
                    new NotBlank()]
            ])
            ->add('slug',HiddenType::class)
            ->add('body',CKEditorType::class)
            ->add('short_description', TextType::class,[
                'constraints' => [
                    new NotBlank()]
            ])
            ->add('article_picture',
                FileType::class, array('mapped' => false))
            ->add('other_file',
                FileType::class, array('mapped' => false))
            ->add('category', EntityType::class,array('class' => Categories::class,'choice_label' => 'title' ))
            ->add('subCategory', EntityType::class,array('class' => SubCategory::class,'choice_label' => 'title' ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
