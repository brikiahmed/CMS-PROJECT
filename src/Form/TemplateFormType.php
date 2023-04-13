<?php
namespace App\Form;

use App\Entity\TemplateForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                // Add any other options or constraints as needed
            ])
            // Other fields in your form
            ->add('isEnabled', ChoiceType::class, [
                'label' => 'Enable Template for Users',
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true, // Render as radio buttons
                'required' => false, // Make it optional
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TemplateForm::class,
        ]);
    }
}
