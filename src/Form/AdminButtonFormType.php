<?php

// src/Form/FieldType.php

namespace App\Form;

use App\Entity\CustomForm\ButtonsForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class AdminButtonFormType extends AbstractType
{
    private $router;
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $filtredRoutes = [];
        $routes = $this->router->getRouteCollection();
        foreach ($routes as $routeName => $route) {
            if (substr($routeName, 0, strlen("_profile")) !== "_profile" &&
                strpos($routeName, "_profile") === false) {
                $filtredRoutes[$routeName] = $routeName;
            }
        }

        $builder
            ->add('label', TextType::class, [
                'label' => 'Button text',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Button Type',
                'choices' => [
                    'Primary Button' => 'btn-primary',
                    'Secondary Button' => 'btn-secondary',
                    'Success Button' => 'btn-success',
                    'Danger Button' => 'btn-danger',
                    'Warning Button' => 'btn-warning',
                ],
            ])
            ->add('path', ChoiceType::class, [
                'label' => 'Path of button',
                'choices' => [
                    'choices' => $filtredRoutes
            ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ButtonsForm::class,
        ]);
    }
}
