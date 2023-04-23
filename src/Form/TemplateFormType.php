<?php
namespace App\Form;

use App\Entity\TemplateForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('isEnabled', CheckboxType::class, [
                'label' => 'Enable Template for Users',
                'attr' => [
                    'class' => 'switch-input' // Add any additional CSS classes as needed
                ],
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
