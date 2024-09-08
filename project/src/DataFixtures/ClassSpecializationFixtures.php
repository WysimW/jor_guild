<?php

namespace App\DataFixtures;

use App\Entity\Classe;
use App\Entity\Specialization;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClassSpecializationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Récupérer les rôles déjà créés
        $tankRole = $manager->getRepository(Role::class)->findOneBy(['name' => 'Tank']);
        $healRole = $manager->getRepository(Role::class)->findOneBy(['name' => 'Heal']);
        $dpsRole = $manager->getRepository(Role::class)->findOneBy(['name' => 'DPS']);

        // Liste des classes et leurs spécialisations traduites
        $classesData = [
            'Guerrier' => [
                ['name' => 'Armes', 'role' => $dpsRole],
                ['name' => 'Fureur', 'role' => $dpsRole],
                ['name' => 'Protection', 'role' => $tankRole]
            ],
            'Paladin' => [
                ['name' => 'Sacré', 'role' => $healRole],
                ['name' => 'Protection', 'role' => $tankRole],
                ['name' => 'Vindicte', 'role' => $dpsRole]
            ],
            'Chasseur' => [
                ['name' => 'Maîtrise des bêtes', 'role' => $dpsRole],
                ['name' => 'Précision', 'role' => $dpsRole],
                ['name' => 'Survie', 'role' => $dpsRole]
            ],
            'Voleur' => [
                ['name' => 'Assassinat', 'role' => $dpsRole],
                ['name' => 'Hors-la-loi', 'role' => $dpsRole],
                ['name' => 'Finesse', 'role' => $dpsRole]
            ],
            'Prêtre' => [
                ['name' => 'Discipline', 'role' => $healRole],
                ['name' => 'Sacré', 'role' => $healRole],
                ['name' => 'Ombre', 'role' => $dpsRole]
            ],
            'Chevalier de la mort' => [
                ['name' => 'Sang', 'role' => $tankRole],
                ['name' => 'Givre', 'role' => $dpsRole],
                ['name' => 'Impie', 'role' => $dpsRole]
            ],
            'Chaman' => [
                ['name' => 'Élémentaire', 'role' => $dpsRole],
                ['name' => 'Amélioration', 'role' => $dpsRole],
                ['name' => 'Restauration', 'role' => $healRole]
            ],
            'Mage' => [
                ['name' => 'Arcanes', 'role' => $dpsRole],
                ['name' => 'Feu', 'role' => $dpsRole],
                ['name' => 'Givre', 'role' => $dpsRole]
            ],
            'Démoniste' => [
                ['name' => 'Affliction', 'role' => $dpsRole],
                ['name' => 'Démonologie', 'role' => $dpsRole],
                ['name' => 'Destruction', 'role' => $dpsRole]
            ],
            'Moine' => [
                ['name' => 'Maître brasseur', 'role' => $tankRole],
                ['name' => 'Tisse-brume', 'role' => $healRole],
                ['name' => 'Marche-vent', 'role' => $dpsRole]
            ],
            'Druide' => [
                ['name' => 'Équilibre', 'role' => $dpsRole],
                ['name' => 'Farouche', 'role' => $dpsRole],
                ['name' => 'Gardien', 'role' => $tankRole],
                ['name' => 'Restauration', 'role' => $healRole]
            ],
            'Chasseur de démons' => [
                ['name' => 'Dévastation', 'role' => $dpsRole],
                ['name' => 'Vengeance', 'role' => $tankRole]
            ],
            'Évocateur' => [
                ['name' => 'Dévastation', 'role' => $dpsRole],
                ['name' => 'Préservation', 'role' => $healRole],
                ['name' => 'Augmentation', 'role' => $dpsRole]
            ],
        ];

        foreach ($classesData as $className => $specializations) {
            // Créer la classe
            $classe = new Classe();
            $classe->setName($className);
            $manager->persist($classe);

            // Créer les spécialisations pour chaque classe
            foreach ($specializations as $specData) {
                $specialization = new Specialization();
                $specialization->setName($specData['name']);
                $specialization->setClasse($classe);
                $specialization->getSpeRole($specData['role']); // Associer le rôle
                $manager->persist($specialization);
            }
        }

        // Sauvegarder les classes et spécialisations en base de données
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class, // Dépend de la fixture des rôles
        ];
    }
}
