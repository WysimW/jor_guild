<?php

namespace App\DataFixtures;

use App\Entity\Character;
use App\Entity\Raid;
use App\Entity\RaidRegister;
use App\Entity\Specialization;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class RegisterAccountFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Créer l'utilisateur
        $user = new User();
        $user->setEmail('register@register.com');

        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'register');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        // Récupérer les classes et spécialisations disponibles
        $classes = $manager->getRepository(\App\Entity\Classe::class)->findAll();
        $specializations = $manager->getRepository(Specialization::class)->findAll();

        // Récupérer le raid depuis la base de données
        $raid = $manager->getRepository(Raid::class)->findOneBy([
            'title' => 'Raid Palais nérubien' // Remplacer par le titre de votre raid
        ]);

        // Créer 20 personnages
        for ($i = 0; $i < 20; $i++) {
            $classe = $faker->randomElement($classes);
            $character = new Character();
            $character->setName($faker->firstName);
            $character->setClasse($classe);
            $character->setUser($user);
            $manager->persist($character);

            // Choisir une spécialisation aléatoire pour ce personnage
            $classSpecializations = array_filter($specializations, function ($specialization) use ($classe) {
                return $specialization->getClasse()->getId() === $classe->getId();
            });

            $randomSpecialization = $faker->randomElement($classSpecializations);

            // Créer une inscription pour le raid
            if ($raid && $randomSpecialization) {
                $raidRegister = new RaidRegister();
                $raidRegister->setRaid($raid);
                $raidRegister->setRegistredCharacter($character);
                $raidRegister->setRegisteredDate(new \DateTime());
                $raidRegister->addRegistredSpecialization($randomSpecialization);

                $manager->persist($raidRegister);
            }
        }

        // Sauvegarder toutes les entités dans la base de données
        $manager->flush();
    }
}
