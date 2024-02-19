<?php

namespace App\Form;

use App\Entity\Voucher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoucherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('expiration')
            ->add('value')
            ->add('usageLimit')
            ->add('type')
            ->add('isValid')
            ->add('isGivenToUser')
            ->add('category')
            ->add('userWon')
            ->add('marketRelated')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voucher::class,
        ]);
    }
}
