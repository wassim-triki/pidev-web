<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\GenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username')
            ->add('password', PasswordType::class)
            // Add other fields as necessary, e.g., gender, date of birth
            ->add('gender', ChoiceType::class, [
                'choices' => GenderEnum::cases(),
                'choice_label' => fn(GenderEnum $enum) => $enum->name,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
            ])
            // Include any other fields you need from your User entity
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}