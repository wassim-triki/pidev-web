<?php
namespace App\Form;

use App\Entity\Market;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MarketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                    'label' => 'Market Name',
                ])
            ->add('region', TextType::class,[
                    'label' => 'Region',
                ])
            ->add('city', TextType::class,[
                    'label' => 'City',
                ])
            ->add('zipCode', TextType::class,[
                    'label' => 'Zip Code',
                ])
            ->add('save', SubmitType::class, ['label' => 'Create Market']);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Market::class,
        ]);
    }
}