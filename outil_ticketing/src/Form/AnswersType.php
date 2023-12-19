<?php

namespace App\Form;

use App\Entity\Answers;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'attr' => ['rows' => '10',
                'class' => 'custom-form'
            ], ] )
            ->add('resoluted_at')
            ->add('docLink', TextType::class, [
                'label' => 'Lien',
                'attr' => [
                    'class' => 'custom-form',
                ],
            ])
            ->add('user', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'custom-form',
                ],
            ])
            ->add('ticket', 
            TextType::class, [
                'label' => 'Nom du ticket',
                'attr' => [
                    'class' => 'custom-form',
                ],
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
