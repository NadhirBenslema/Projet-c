<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\NiveauDifficulte;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('date')
            ->add('frais')
            ->add('id_niveau', EntityType::class, [
                // looks for choices from this entity
                'class' => NiveauDifficulte::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'niveau',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
