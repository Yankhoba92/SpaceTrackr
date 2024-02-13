<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD:src/Form/ProfilType.php
            ->add('username', null, [
                'required' => false
            ])
=======
            ->add('email', EmailType::class,)
            ->add('password', PasswordType::class)
            ->add('username')
            ->add('roles')
>>>>>>> 14a5f7bed0e13310054f37641a888ffa34131491:src/Form/UserType.php
            ->add('userPicture', FileType::class , [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/*'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
