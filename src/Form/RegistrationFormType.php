<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email'
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Mot de passe'
            ])
            ->add('lastName', TextType::class,[
                'label' => 'Nom de famille'
            ])
            ->add('firstName', TextType::class,[
                'label' => 'Prénom'
            ])
            ->add('adress', TextType::class,[
                'label' => 'Adresse'
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'M.' => 'M.',
                    'Mme' => 'Mme',
                ],
                'label' => 'Civilité'
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Date d\'anniversaire',
                'widget' => 'choice',
                'format' => 'y-M-d',
                'years' => range(date("Y") - 110, date("Y") - 18)
            ])
            ->add('account', NumberType::class, [
                'label' => 'Premier apport'
            ])
            ->add('fileIdCardImg', VichFileType::class,[
                'label' => 'Pièce d\'identité'
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Veuillez acceptez nos conditions d\'utilisation',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez acceptez nos conditions d\'utilisations.',
                    ]),
                ],
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
