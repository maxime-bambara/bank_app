<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class)
            ->add('lastName', TextType::class)
            ->add('firstName', TextType::class)
            ->add('adress', TextType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'M.' => 'Mr.',
                    'Mme' => 'Mme',
                ],
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'En cours' => 'En cours',
                    'Validé' => 'Validé',
                ],
                ])
            ->add('birthday', DateType::class, [
                'widget' => 'choice',
                'format' => 'y-M-d',
                'years' => range(date("Y") - 110, date("Y") - 18)
            ])
            ->add('account', NumberType::class, [
                'label' => 'First amount'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
