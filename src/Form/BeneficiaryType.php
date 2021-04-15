<?php

namespace App\Form;

use App\Entity\Beneficiary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BeneficiaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class,[
                'label' => 'Nom de famille'
                ])
            ->add('firstName', TextType::class,[
                'label' => 'Prénom'
                ])
            ->add('wording', TextType::class,[
                'label' => 'Libellé'
                ])
            ->add('iban', TextType::class,[
                'label' => 'Iban'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Beneficiary::class,
        ]);
    }
}
