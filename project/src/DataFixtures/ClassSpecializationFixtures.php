<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use App\Entity\Specialization;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClassSpecializationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Liste des classes et leurs spécialisations
        $classesData = [
            'Warrior' => ['Arms', 'Fury', 'Protection'],
            'Paladin' => ['Holy', 'Protection', 'Retribution'],
            'Hunter' => ['Beast Mastery', 'Marksmanship', 'Survival'],
            'Rogue' => ['Assassination', 'Outlaw', 'Subtlety'],
            'Priest' => ['Discipline', 'Holy', 'Shadow'],
            'Death Knight' => ['Blood', 'Frost', 'Unholy'],
            'Shaman' => ['Elemental', 'Enhancement', 'Restoration'],
            'Mage' => ['Arcane', 'Fire', 'Frost'],
            'Warlock' => ['Affliction', 'Demonology', 'Destruction'],
            'Monk' => ['Brewmaster', 'Mistweaver', 'Windwalker'],
            'Druid' => ['Balance', 'Feral', 'Guardian', 'Restoration'],
            'Demon Hunter' => ['Havoc', 'Vengeance'],
            'Evoker' => ['Devastation', 'Preservation', 'Augmentation'] // Evoker avec la nouvelle spécialisation Augmentation
        ];

        foreach ($classesData as $className => $specializations) {
            // Créer la classe
            $classe = new Classe();
            $classe->setName($className);
            $manager->persist($classe);

            // Créer les spécialisations pour chaque classe
            foreach ($specializations as $specName) {
                $specialization = new Specialization();
                $specialization->setName($specName);
                $specialization->setClasse($classe);
                $manager->persist($specialization);
            }
        }

        // Sauvegarder les classes et spécialisations en base de données
        $manager->flush();
    }
}
