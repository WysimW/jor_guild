<?php

// src/Form/RaidType.php

namespace App\Form;

use App\Entity\Boss;
use App\Entity\Raid;
use App\Entity\RaidTier;
use App\Repository\BossRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// Ajoutez ceci si vous ne l'avez pas déjà
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RaidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Vos autres champs existants
            ->add('title', null, [
                'label' => 'Nom du Raid',
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date du Raid',
                'widget' => 'single_text',
            ])
            ->add('raidTier', EntityType::class, [
                'class' => RaidTier::class,
                'choice_label' => 'name',
                'label' => 'RaidTier associé',
            ])
            // Ajoutez le champ downBosses
            ->add('downBosses', EntityType::class, [
                'class' => Boss::class,
                'choice_label' => 'name',
                'label' => 'Boss vaincus',
                'multiple' => true,
                'expanded' => true, // Utilisez des cases à cocher
                'query_builder' => function (BossRepository $er) use ($options) {
                    // Filtrer les boss en fonction du RaidTier sélectionné
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.name', 'ASC');
                },
            ])
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    'Normal' => 'Normal',
                    'Héroïque' => 'Héroïque',
                    'Mythique' => 'Mythique',
                ],
                'label' => 'Difficulté',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Raid::class,
        ]);
    }
}
