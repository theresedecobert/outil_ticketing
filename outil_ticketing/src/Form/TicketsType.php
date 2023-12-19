<?php

namespace App\Form;

use App\Entity\Tickets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Ouvert' => 'Ouvert',
                    'Fermé' => 'Fermé',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'custom-form',
                ],
            ])
            ->add(
                'content', TextareaType::class,
                [
                    'label' => 'Description',
                    'attr' => [
                        'rows' => '10',
                        'class' => 'custom-form'
                    ],
                ]
            )
            ->add('created_at')
            ->add('user')
            ->add('files', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}
