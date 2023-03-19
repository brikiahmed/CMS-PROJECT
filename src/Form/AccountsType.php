<?php

namespace App\Form;

use App\Entity\Accounts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;


class AccountsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ]
            ])
            ->add('roles', TextType::class,[
                'constraints' => [
                    new NotBlank()]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'required' => true,

            ])
            ->add('name', TextType::class)
            ->add('first_name', TextType::class)
            ->add('last_name', TextType::class)
            ->add('note', TextareaType::class)
            ->add('added_on', DateType::class,['label' => 'Date mise a jour:   : ','widget' => 'single_text'])
            ->add('imageFile',FileType::class, array('mapped' => false))
            ->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) > 0 ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    return [$rolesString];
                }
            ));




        ;
    }

    




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accounts::class,
        ]);
    }

    
 



}
