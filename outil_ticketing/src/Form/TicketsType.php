<?php

namespace App\Form;


use App\Entity\Tickets;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isAuthor = $options['is_author'];

        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'ouvert' => 'ouvert',
                    'fermé' => 'fermé',
                ],
                'expanded' => true, // To use radio buttons
                'disabled' => !$isAuthor && !$options['is_admin'], // Unable only admin or author to edit the status
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'custom-form',
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => '10',
                    'class' => 'custom-form'
                ],
            ])
            ->add('created_at')
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
            'is_admin' => false,
            'is_author' => false,
        ]);
    }
}
