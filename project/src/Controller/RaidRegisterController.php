<?php

// src/Controller/RaidRegisterController.php

namespace App\Controller;

use App\Entity\RaidRegister;
use App\Entity\Character;
use App\Entity\Raid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RaidRegisterController extends AbstractController
{
    #[Route('/api/raid/register', name: 'raid_register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Récupérer les données envoyées
        $data = json_decode($request->getContent(), true);
        $raidId = $data['raid_id'] ?? null;
        $characterId = $data['character_id'] ?? null;

        // Vérifier que l'utilisateur est bien connecté
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour vous inscrire à un raid.');
        }

        // Vérifier si le raid existe
        $raid = $em->getRepository(Raid::class)->find($raidId);
        if (!$raid) {
            return new JsonResponse(['error' => 'Raid non trouvé.'], 404);
        }

        // Vérifier si le personnage existe et appartient à l'utilisateur
        $character = $em->getRepository(Character::class)->find($characterId);
        if (!$character || $character->getUser() !== $user) {
            return new JsonResponse(['error' => 'Personnage non trouvé ou n\'appartenant pas à cet utilisateur.'], 404);
        }

        // Vérifier si le personnage est déjà inscrit au raid dans RaidRegister
        $existingRegistration = $em->getRepository(RaidRegister::class)->findOneBy([
            'raid' => $raid,
            'registredCharacter' => $character
        ]);

        if ($existingRegistration) {
            return new JsonResponse(['error' => 'Le personnage est déjà inscrit à ce raid.'], 400);
        }

        // Créer une nouvelle inscription dans RaidRegister
        $raidRegister = new RaidRegister();
        $raidRegister->setRaid($raid);
        $raidRegister->setRegistredCharacter($character);
        $raidRegister->setRegisteredDate(new \DateTime()); // Ajouter la date d'inscription

        // Sauvegarder l'inscription
        $em->persist($raidRegister);
        $em->flush();

        return new JsonResponse(['message' => 'Inscription réussie.'], 201);
    }
}
