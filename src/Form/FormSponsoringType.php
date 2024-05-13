<?php

namespace App\Form;

use App\Entity\Sponsoring;
use App\Enum\ContratEnum;
use App\Enum\TypeetatEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FormSponsoringType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('date')
            ->add('contrat', ChoiceType::class, [
                'choices' => [
                    'ONE_YEARS' => ContratEnum::ANS1->value,
                    'TWO_YEARS' => ContratEnum::ANS2->value,
                    'THREE_YEARS' => ContratEnum::ANS3->value,
                ],
                'expanded' => false,
                'multiple' => false,
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Type:',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'ACTIVE' => TypeetatEnum::ACTIVE->value,
                    'DESACTIVE' => TypeetatEnum::DESACTIVE->value,
                ],
                'expanded' => false,
                'multiple' => false,
                'label_attr' => ['class' => 'form-label'],
                'label' => 'Type:',
            ])

            ->add('image', FileType::class, [
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
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sponsoring::class,
        ]);
    }
}
