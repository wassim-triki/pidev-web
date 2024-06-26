<?php

namespace App\Form;

use App\Entity\Answer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('body', TextareaType::class, [
            'attr' => ['class' => 'form-control'],
            'label' => 'Answer : ',
            'label_attr' => ['class' => 'col-auto']
        ])->add('save', SubmitType::class, [
            'attr' => ['class' => 'btn btn-info mx-auto d-block'],
            'label' => 'Answer'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}
