<?php

namespace App\DataFixtures;

use App\Entity\Raid;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un utilisateur administrateur
        $adminUser = new User();
        $adminUser->setEmail('admin@admin.com');
        $adminUser->setRoles(['ROLE_ADMIN']);

        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword(
            $adminUser,
            'admin'
        );
        $adminUser->setPassword($hashedPassword);

        $manager->persist($adminUser);

        // Créer un raid le mercredi 12 septembre 2024 à 21 heures
        $raid = new Raid();
        $raid->setTitle('Raid du Palais Nérubien');
        $raid->setDescription('Raid en mode normal, première soirée.');
        $raid->setDate(new \DateTime('2024-09-12 21:00:00'));

        $manager->persist($raid);

        // Sauvegarder les entités en base de données
        $manager->flush();
    }
}
