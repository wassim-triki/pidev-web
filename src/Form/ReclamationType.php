<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TypeReclamationEnum;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('subject')
        ->add('description')
        ->add('EmailReportedUser')
        ->add('TypeReclamation', ChoiceType::class, [
            'choices' => array_flip(TypeReclamationEnum::getTypes()),
            'label' => 'Type de Réclamation',
            'choice_label' => function ($value, $key, $index) {
                return $value;
            },
            'placeholder' => 'Choisir...',
        ])
        ->add('screenShot', FileType::class, [
            'label' => 'screenShot'
            
        ])
        
        ->add('save', SubmitType::class, [
            'label' => 'Enregistrer',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
