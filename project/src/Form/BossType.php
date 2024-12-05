<?php

namespace App\Form;

use App\Entity\Boss;
use App\Entity\Raid;
use App\Entity\RaidTier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BossType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('raidTier', EntityType::class, [
                'class' => RaidTier::class,
                'choice_label' => 'name',
            ])
            ->add('orderInRaid', IntegerType::class, [
                'label' => 'Ordre dans le Raid',
                'attr' => [
                    'min' => 1,
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boss::class,
        ]);
    }
}
