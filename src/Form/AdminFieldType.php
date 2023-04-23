<?php

// src/Form/FieldType.php

namespace App\Form;

use App\Entity\CustomForm\FieldForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Field Label',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Field Type',
                'choices' => [
                    'Text' => 'text',
                    'Email' => 'email',
                    'Password' => 'password',
                    'Checkbox' => 'checkbox',
                    'Select' => 'select',
                    'Textarea' => 'textarea',
                    'Date' => 'date',
                    // add more field types as needed
                ],
            ])
            ->add('isRequired', CheckboxType::class, [
                'label' => 'Is required ?',
                'attr' => [
                    'class' => 'switch-input' // Add any additional CSS classes as needed
                ],
                'required' => false, // Make it optional
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FieldForm::class,
        ]);
    }
}
