<?php

namespace App\Form;

use App\Entity\Post;
use App\Enum\PostTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('date')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Lost' => 'LOST',
                    'Found' => 'FOUNDED',
                ],
                'mapped' => true,
            ])
            ->add('imageUrl')
            ->add('place')
            ->add('save', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
