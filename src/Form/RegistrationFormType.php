<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\GenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('address', TextType::class, [
                'required' => false, // Depending on your application's requirements
            ])
            ->add('phone', TextType::class, [
                'required' => false, // Depending on your application's requirements
            ])
            ->add('photo', FileType::class, [
                'label' => 'Profile Photo (Image file)',
                'mapped' => false, // This field is not mapped to any entity property
                'required' => false,
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
            ->add('gender', ChoiceType::class, [
                'choices' => GenderEnum::cases(),
                'choice_label' => function(?GenderEnum $gender) {
                    return $gender ? $gender->name : '';
                },
                'placeholder' => 'Choose your gender',
                'expanded' => false, // This is what changes the field from radio buttons to a dropdown
                'multiple' => false,
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                // 'format' => 'yyyy-MM-dd', // Uncomment if you want to specify the format
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
