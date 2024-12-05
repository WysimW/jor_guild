<?php

namespace App\Form;

use App\Entity\Raid;
use App\Entity\RaidTier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RaidType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('raidTier', EntityType::class, [
                'class' => RaidTier::class,
                'choice_label' => 'name',
                'label' => 'RaidTier associé',
            ])
            ->add('difficulty', ChoiceType::class, [
                'choices' => [
                    'Normal' => 'Normal',
                    'Héroïque' => 'Héroïque',
                    'Mythique' => 'Mythique',
                ],
                'label' => 'Difficulté',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Raid::class,
        ]);
    }
}
