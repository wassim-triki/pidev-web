<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\GenderEnum;
use App\Validator\Constraints\UniqueUsername;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AdminEditProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['constraints'=>[
//                new UniqueUsername()
            ],
                'attr' => ['id' => 'username', 'class' => 'form-control', 'placeholder' => 'Enter username'],
                'label' => 'Username',
                'label_attr' => ['class' => 'form-label'],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['id' => 'example-email', 'class' => 'form-control', 'placeholder' => 'Enter email'],
                'label' => 'Email',
                'label_attr' => ['class' => 'form-label'],
            ])
//            ->add('password', PasswordType::class, [
//                'attr' => ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Enter password'],
//                'label' => 'Password',
//                'label_attr' => ['class' => 'form-label'],
//                'required' => false, // Make the field optional
//                'mapped' => false, // Do not map this field directly to the entity
//            ])
            ->add('address', TextType::class, [
                'attr' => ['id' => 'address', 'class' => 'form-control', 'placeholder' => 'Enter address'],
                'label' => 'Address',
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'attr' => ['id' => 'phone', 'class' => 'form-control', 'placeholder' => 'Enter phone number'],
                'label' => 'Phone',
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
            ])
//            ->add('gender', ChoiceType::class, [
//                'choices' => GenderEnum::cases(),
//                'choice_label' => function (?GenderEnum $gender) {
//                    return $gender ? ucwords(strtolower(str_replace('_', ' ', $gender->value))) : '';
//                },
//                'attr' => ['class' => 'form-control select2', 'placeholder' => 'Select gender'],
//                'label' => 'Gender',
//                'label_attr' => ['class' => 'form-label'],
//                'placeholder' => 'Select gender',
//                'expanded' => false,
//                'multiple' => false,
//            ])
//            ->add('roles', ChoiceType::class, [
//                'choices' => [
//                    'User' => 'ROLE_USER',
//                    'Admin' => 'ROLE_ADMIN',
//                ],
//                'attr' => ['class' => 'select2 form-control select2-multiple', 'data-toggle' => 'select2', 'multiple' => 'multiple', 'data-placeholder' => 'Select roles'],
//                'label' => 'Roles',
//                'expanded' => false,
//                'multiple' => true, // Allow multiple selections
//            ])
            ->add('photo', FileType::class, [
                'attr' => ['id' => 'photo', 'class' => 'form-control', 'placeholder' => 'Upload avatar'],
                'label' => 'Avatar',
                'label_attr' => ['class' => 'form-label'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-3'],
                'label' => 'Submit',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
