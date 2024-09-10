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
        $raidRegister->setStatus($data['status']);
        $raidRegister->addRegistredSpecialization($specialization);
        $raidRegister->setRegisteredDate(new \DateTime()); // Ajouter la date d'inscription


        // Sauvegarder dans la base de données
        $em->persist($raidRegister);
        $em->flush();

        return new JsonResponse(['message' => 'Inscription réussie.'], 201);
    }

    #[Route('/api/raid/register/{id}', name: 'raid_register_edit', methods: ['PUT'])]
    public function editRaidRegistration(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Vous devez être connecté pour modifier votre inscription.'], 401);
        }

        // Récupérer l'inscription au raid
        $raidRegister = $em->getRepository(RaidRegister::class)->find($id);
        if (!$raidRegister) {
            return new JsonResponse(['error' => 'Inscription non trouvée.'], 404);
        }

        // Vérifier que l'utilisateur est bien le propriétaire du personnage inscrit
        if ($raidRegister->getRegistredCharacter()->getUser() !== $user) {
            return new JsonResponse(['error' => 'Vous ne pouvez modifier que vos propres inscriptions.'], 403);
        }

        // Récupérer le raid si nécessaire (si l'ID du raid peut être modifié)
        $raid = $em->getRepository(Raid::class)->find($data['raid_id']);
        if (!$raid) {
            return new JsonResponse(['error' => 'Raid non trouvé.'], 404);
        }

        // Récupérer le personnage sélectionné si celui-ci peut être modifié
        $character = $em->getRepository(Character::class)->find($data['character_id']);
        if (!$character || $character->getUser() !== $user) {
            return new JsonResponse(['error' => 'Personnage non valide.'], 404);
        }

        // Récupérer la nouvelle spécialisation
        $specialization = $em->getRepository(Specialization::class)->find($data['specialization_id']);
        if (!$specialization) {
            return new JsonResponse(['error' => 'Spécialisation non trouvée.'], 404);
        }

        // Mettre à jour l'inscription au raid
        $raidRegister->setRaid($raid);
        $raidRegister->setRegistredCharacter($character);
        $raidRegister->setStatus($data['status']);
        $raidRegister->getRegistredSpecialization()->clear(); // Supprimer les spécialisations actuelles
        $raidRegister->addRegistredSpecialization($specialization);
        $raidRegister->setRegisteredDate(new \DateTime()); // Mettre à jour la date d'inscription

        // Sauvegarder les modifications
        $em->flush();

        return new JsonResponse(['message' => 'Inscription modifiée avec succès.'], 200);
    }

    #[Route('/api/raid/edit/{raidId}/{characterId}', name: 'raid_register_edit_forlist', methods: ['PUT'])]
public function editRaidRegistrationforList(int $raidId, int $characterId, Request $request, EntityManagerInterface $em): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Vérifier si l'utilisateur est connecté
    $user = $this->getUser();
    if (!$user) {
        return new JsonResponse(['error' => 'Vous devez être connecté pour modifier votre inscription.'], 401);
    }

    // Récupérer le raid et l'inscription existante
    $raidRegister = $em->getRepository(RaidRegister::class)
        ->findOneBy(['raid' => $raidId, 'registredCharacter' => $characterId]);

    if (!$raidRegister) {
        return new JsonResponse(['error' => 'Inscription non trouvée.'], 404);
    }

    // Vérifier que l'utilisateur est bien le propriétaire du personnage inscrit
    if ($raidRegister->getRegistredCharacter()->getUser() !== $user) {
        return new JsonResponse(['error' => 'Vous ne pouvez modifier que vos propres inscriptions.'], 403);
    }

    // Récupérer la nouvelle spécialisation
    $specialization = $em->getRepository(Specialization::class)->find($data['specialization_id']);
    if (!$specialization) {
        return new JsonResponse(['error' => 'Spécialisation non trouvée.'], 404);
    }

    // Mettre à jour l'inscription avec la nouvelle spécialisation
    $raidRegister->getRegistredSpecialization()->clear(); // Supprimer les spécialisations actuelles
    $raidRegister->addRegistredSpecialization($specialization);
    $raidRegister->setRegisteredDate(new \DateTime()); // Mettre à jour la date d'inscription
    $raidRegister->setStatus($data['status']);

    // Sauvegarder les modifications
    $em->flush();

    return new JsonResponse(['message' => 'Inscription modifiée avec succès.'], 200);
}

}
