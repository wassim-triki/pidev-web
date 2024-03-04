<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Titre',
            'label_attr' => ['class' => 'form-label']
        ])
        ->add('body', TextareaType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Contenu',
            'label_attr' => ['class' => 'form-label']
        ])
        ->add('save', SubmitType::class, [
            'attr' => ['class' => 'btn btn-warning mx-auto d-block'],
            'label' => 'Questionner'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
