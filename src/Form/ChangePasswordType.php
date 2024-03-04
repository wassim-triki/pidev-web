<?php

// src/Form/ChangePasswordType.php

namespace App\Form;

use App\Validator\Constraints\Password;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Old Password*',
                'attr' => ['placeholder' => 'Enter Old Password'],
                'constraints' => [
                    new Password(),
                    new NotBlank([
                        'message' => 'Please enter your old password',
                    ]),
                ],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'New Password*',
                'attr' => ['placeholder' => 'Enter New Password'],
                'constraints' => [
                    new Password(),
                    new NotBlank([
                        'message' => 'Please enter your new password',
                    ]),
                    // Add more constraints as needed
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'setting-save-btn'],
            ]);
    }
}
