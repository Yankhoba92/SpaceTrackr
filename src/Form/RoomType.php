<?php

namespace App\Form;

use App\Entity\Feature;
use App\Entity\Material;
use App\Entity\Room;
use App\Entity\Software;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('city')
            ->add('zipCode')
            ->add('address')
            ->add('capacity')
            ->add('description')
            ->add('price')
            ->add('roomPicture')
            ->add('user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('features', EntityType::class, [
                'class' => Feature::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('materials', EntityType::class, [
                'class' => Material::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('software', EntityType::class, [
                'class' => Software::class,
'choice_label' => 'id',
'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
