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
                'label'=>'Votre réponse',
                'attr' => [
                    'rows' => '10',
                    'class' => 'custom-form'],
            ])
            ->add('docLink', TextType::class, [
                'label' => 'Lien vers la doc',
                'attr' => [
                    'class' => 'custom-form',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answers::class,
        ]);
    }
}
