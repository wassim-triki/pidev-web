<?php

// src/Form/EmailChangeFormType.php

namespace App\Form;

use App\Validator\Constraints\UniqueEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class EmailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class,[
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter your new email address',
                ]),
            ],
        ])
            ->add('submit', SubmitType::class, [
                'label' => 'Send reset link',
                'attr' => ['class' => 'send-m-btn'],
            ]);
    }
}
