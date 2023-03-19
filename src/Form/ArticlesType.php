<?php

namespace App\Form;

use App\Entity\Articles;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('url')
            ->add('slug',HiddenType::class)
            ->add('body',CKEditorType::class)
            ->add('short_description')
            ->add('article_picture')
            ->add('other_file')
            ->add('tags')
            ->add('articles_categories')
            ->add('articles_sub_category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
