<?php

// src/Controller/RaidRegisterController.php

namespace App\Controller;

use App\Entity\Raid;
use App\Entity\Character;
use App\Entity\RaidRegister;
use App\Entity\Specialization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RaidRegisterController extends AbstractController
{
    #[Route('/api/raid/register', name: 'raid_register', methods: ['POST'])]
public function registerForRaid(Request $request, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);
    
    $user = $this->getUser();
    if (!$user) {
        return new JsonResponse(['error' => 'Vous devez être connecté pour vous inscrire à un raid.'], 401);
    }

    // Récupérer le raid
    $raid = $em->getRepository(Raid::class)->find($data['raid_id']);
    if (!$raid) {
        return new JsonResponse(['error' => 'Raid non trouvé.'], 404);
    }

    // Récupérer le personnage sélectionné
    $character = $em->getRepository(Character::class)->find($data['character_id']);
    if (!$character || $character->getUser() !== $user) {
        return new JsonResponse(['error' => 'Personnage non valide.'], 404);
    }

    // Récupérer la spécialisation sélectionnée
    $specialization = $em->getRepository(Specialization::class)->find($data['specialization_id']);
    if (!$specialization) {
        return new JsonResponse(['error' => 'Spécialisation non trouvée.'], 404);
    }

    // Créer l'inscription au raid
    $raidRegister = new RaidRegister();
    $raidRegister->setRaid($raid);
    $raidRegister->setRegistredCharacter($character);
    $raidRegister->addRegistredSpecialization($specialization);
    $raidRegister->setRegisteredDate(new \DateTime()); // Ajouter la date d'inscription


    // Sauvegarder dans la base de données
    $em->persist($raidRegister);
    $em->flush();

    return new JsonResponse(['message' => 'Inscription réussie.'], 201);
}
}
