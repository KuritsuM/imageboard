<?php

namespace App\Form;

use App\Entity\Threads;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('theme', null, [
                'attr' => [
                    'placeholder' => 'Тема',
                    'class' => 'TODO'
                ],
                'label' => ''
            ])
            ->add('text', null, [
                'attr' => [
                    'placeholder' => 'Текст (не более 15.000 символов)',
                    'class' => 'TODO'
                ]
            ])
            ->add('file1', FileType::class, [
                'label' => 'Выбрать файл'
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Отправить'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Threads::class,
        ]);
    }
}
