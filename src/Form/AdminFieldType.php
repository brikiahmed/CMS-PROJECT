<?php

// src/Form/FieldType.php

namespace App\Form;

use App\Entity\FieldForm;
use Symfony\Component\Form\AbstractType;
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
                    'Date' => 'date',
                    'Checkbox' => 'checkbox',
                    // add more field types as needed
                ],
            ])
            ->add('options', CollectionType::class, [
                'entry_type' => TextType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Field Options (comma-separated)',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FieldForm::class,
        ]);
    }
}
