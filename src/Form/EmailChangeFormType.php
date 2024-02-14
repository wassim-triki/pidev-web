<?php

// src/Form/EmailChangeFormType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class EmailChangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldEmail', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your old email address',
                    ]),
                ],
                'attr' => ['autocomplete' => 'email', 'placeholder' => 'Old Email Address'],
                'label' => 'Old Email Address*',
            ])
            ->add('newEmail', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your new email address',
                    ]),
                ],
                'attr' => ['autocomplete' => 'email', 'placeholder' => 'New Email Address'],
                'label' => 'New Email Address*',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'setting-save-btn'],
            ]);
    }
}
