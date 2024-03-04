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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('subject')
        ->add('description')
        ->add('ReportedUsername')
        ->add('TypeReclamation', ChoiceType::class, [
            'choices' => array_flip(TypeReclamationEnum::getTypes()),
            'label' => 'Type de Réclamation',
            'choice_label' => function ($value, $key, $index) {
                return $value;
            },
            'placeholder' => 'Choisir...',
        ])
        ->add('screenShot', FileType::class, [
            'label' => 'screenShot',
            'required' => false, 
            'mapped' => false, 
            'constraints' => [
                new File([
                    'maxSize' => '1024k', 
                    'mimeTypes' => [
                        'image/*', // Accepte uniquement les fichiers de type image
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide', // Message d'erreur personnalisé pour le type MIME
                ])
            ],
        ])
        ->add('save', SubmitType::class, [
            'label' => 'save',
            'attr' => ['class' => 'send-m-btn'],
        ]);
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Reclamation::class,
    ]);
}







}
