<?php

namespace App\Form;

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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class AccountInformationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                'constraints'=>[
                    new UniqueUsername()
                ]
            ])
            ->add('address', TextType::class, [
                // Add any constraints if needed
            ])
            ->add('phone', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(\+216)?\d{8}$/',
                        'message' => 'Phone number must be exactly 8 digits or in the format +216xxxxxxxx',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'setting-save-btn'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
