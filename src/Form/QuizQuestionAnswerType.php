<?php

namespace App\Form;

use App\Shared\Transfer\QuizQuestionAnswerTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizQuestionAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('questionId', HiddenType::class)
            ->add('answerIds', CollectionType::class, [
                'entry_type' => HiddenType::class,
                'required'  => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuizQuestionAnswerTransfer::class,
            'csrf_protection' => false,
        ]);
    }
}
