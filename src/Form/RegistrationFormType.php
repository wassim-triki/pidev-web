<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\GenderEnum;
use App\Validator\Constraints\UniqueUsername;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                'constraints'=>[
                    new UniqueUsername()
                ]
            ])
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('address', TextType::class, [
//                'required' => false,
            ])
            ->add('phone', TextType::class, [
//                'required' => false,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Profile Photo (Image file)',
                'mapped' => false,
//                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => GenderEnum::cases(),
                'choice_label' => function(?GenderEnum $gender) {
                    return $gender ? ucwords(strtolower(str_replace('_', ' ', $gender->value))) : '';
                },
                'placeholder' => 'Choose your gender',
                'expanded' => false,
                'multiple' => false,
            ])
            // Add the submit button here
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'login-btn'],
                'label' => 'Register Now',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'registration'], // Include 'registration' group
        ]);
    }




}
