<?php

// src/Controller/CharacterController.php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CharacterController extends AbstractController
{
    #[Route('/api/characters', name: 'browse_characters', methods: ['GET'])]
    public function browse(EntityManagerInterface $em): JsonResponse
    {
        $characters = $em->getRepository(Character::class)->findAll();
        return $this->json($characters);
    }

    #[Route('/api/characters/list', name: 'character_list', methods: ['GET'])]
    public function getCharacters(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        // Debug : Vérifier si l'utilisateur est correctement récupéré
    
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non authentifié.'], 401);
        }
    
        $characters = $em->getRepository(Character::class)->findBy(['user' => $user]);
    
        return $this->json($characters, 200, [], ['groups' => 'character:read']);
    }

    #[Route('/api/characters/{id}', name: 'read_character', methods: ['GET'])]
    public function read(int $id, EntityManagerInterface $em): JsonResponse
    {
        $character = $em->getRepository(Character::class)->find($id);
        if (!$character) {
            return new JsonResponse(['error' => 'Personnage non trouvé'], 404);
        }

        return $this->json($character);
    }

    #[Route('/api/characters', name: 'add_character', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $character = new Character();
        $character->setName($data['name']);
        $character->getRaidRoles($data['role']);

        $em->persist($character);
        $em->flush();

        return new JsonResponse(['message' => 'Personnage créé avec succès'], 201);
    }

    #[Route('/api/characters/{id}', name: 'edit_character', methods: ['PUT'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $character = $em->getRepository(Character::class)->find($id);
        if (!$character) {
            return new JsonResponse(['error' => 'Personnage non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $character->setName($data['name']);
        $character->setRole($data['role']);

        $em->flush();

        return new JsonResponse(['message' => 'Personnage mis à jour avec succès']);
    }

    #[Route('/api/characters/{id}', name: 'delete_character', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $character = $em->getRepository(Character::class)->find($id);
        if (!$character) {
            return new JsonResponse(['error' => 'Personnage non trouvé'], 404);
        }

        $em->remove($character);
        $em->flush();

        return new JsonResponse(['message' => 'Personnage supprimé avec succès']);
    }

    #[Route('/api/character/create', name: 'character_create', methods: ['POST'])]
    public function addCharacter(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour créer un personnage.');
        }

        $data = json_decode($request->getContent(), true);

        // Récupérer le rôle depuis la base de données
        $role = $em->getRepository(Role::class)->find($data['role_id']);
        if (!$role) {
            return new JsonResponse(['error' => 'Rôle non trouvé.'], 404);
        }

        // Créer le nouveau personnage
        $character = new Character();
        $character->setName($data['name']);
        $character->addRaidRole($role); // Associer le rôle au personnage
        $character->setUser($user); // Associer le personnage à l'utilisateur connecté

        // Sauvegarder le personnage dans la base de données
        $em->persist($character);
        $em->flush();

        return new JsonResponse(['message' => 'Personnage créé avec succès.'], 201);
    }
}
