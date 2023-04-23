<?php

// src/Form/RegistrationFormType.php

namespace App\Form;

use App\Entity\CustomForm\CmsForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminCmsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Form Title',
            ])
            ->add('isEnabled', CheckboxType::class, [
                'label' => 'Enable Template for Users',
                'attr' => [
                    'class' => 'switch-input' // Add any additional CSS classes as needed
                ],
                'required' => false, // Make it optional
            ])
            ->add('fields', CollectionType::class, [
                'entry_type' => AdminFieldType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('buttons', CollectionType::class, [
                'entry_type' => AdminButtonFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsForm::class,
        ]);
    }
}
