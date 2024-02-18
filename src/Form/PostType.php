<?php

namespace App\Form;

use App\Entity\Post;
use App\Enum\PostTypeEnum;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('type', ChoiceType::class, [
            'choices' => [
            'Lost' => PostTypeEnum::LOST->value,
            'Found' => PostTypeEnum::FOUND->value,
            ],
            'expanded' => false,
            'multiple' => false,
            'label_attr' => ['class' => 'form-label'],
            'label' => 'Type:',
            ])
            ->add('imageUrl', FileType::class, [
                'label' => 'Image',
                'required' => true,
                'data_class' => null,  // Set data_class to null
            ])            
            ->add('place')           
            ->add('save', SubmitType::class);
            // In your form type class
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
