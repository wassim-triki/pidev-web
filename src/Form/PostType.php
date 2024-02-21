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
use Symfony\Component\Validator\Constraints\File;

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
                'expanded' => true, // Changed to true
                'multiple' => false,
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Type:',
            ])
            ->add('imageUrl', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('place')
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'main-btn btn-hover h-40 w-100 mt-37 mb-3'
                ],
                'label' => 'Save'
            ]);
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
