<?php

namespace App\Form;

use App\Entity\Answers;
use App\Entity\Tickets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnswersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
 
        $builder
            ->add('description', TextareaType::class, [
                'label'=>'Description',
                'attr' => [
                    'rows' => '10',
                    'class' => 'custom-form'],
            ])
            ->add('docLink', TextType::class, [
                'label' => 'Lien de la doc',
                'attr' => [
                    'class' => 'custom-form',
                ],
            ])
            ->add('ticket', EntityType::class, [
                'class' => Tickets::class,
                'choice_label' => function (Tickets $ticket) {
                    return $ticket->getTitle();
                },
                'attr' => ['class' => 'custom-form'],
                'label' => 'Nom du ticket',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answers::class,
        ]);
    }
}
