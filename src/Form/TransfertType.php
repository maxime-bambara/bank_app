<?php

namespace App\Form;

use App\Entity\Transfert;
use App\Entity\Beneficiary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransfertType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
    $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $user = $this->token->getToken()->getUser(); ;

        $builder
            ->add('amount', NumberType::class)
            ->add('beneficiary', EntityType::class, [
                'class' => Beneficiary::class,
                'choices' => $user->getBeneficiaries()->getValues(),
                'choice_label' => 'wording',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transfert::class,
        ]);
    }
}
