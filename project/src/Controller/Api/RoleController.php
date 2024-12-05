<?php

// src/Controller/RoleController.php

namespace App\Controller\Api;

use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RoleController extends AbstractController
{
    #[Route('/api/roles', name: 'role_list', methods: ['GET'])]
    public function getRoles(EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $roles = $em->getRepository(Role::class)->findAll();

        // Sérialiser les rôles avec le Serializer Symfony
        $jsonRoles = $serializer->serialize($roles, 'json', ['groups' => 'role:read']);

        return new JsonResponse($jsonRoles, 200, [], true); // true pour indiquer que la réponse est déjà au format JSON
    }

    #[Route('/api/roles/{id}', name: 'read_role', methods: ['GET'])]
    public function read(int $id, EntityManagerInterface $em): JsonResponse
    {
        $role = $em->getRepository(Role::class)->find($id);
        if (!$role) {
            return new JsonResponse(['error' => 'Rôle non trouvé'], 404);
        }

        return $this->json($role);
    }

    #[Route('/api/roles', name: 'add_role', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $role = new Role();
        $role->setName($data['name']);

        $em->persist($role);
        $em->flush();

        return new JsonResponse(['message' => 'Rôle créé avec succès'], 201);
    }

    #[Route('/api/roles/{id}', name: 'edit_role', methods: ['PUT'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $role = $em->getRepository(Role::class)->find($id);
        if (!$role) {
            return new JsonResponse(['error' => 'Rôle non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $role->setName($data['name']);

        $em->flush();

        return new JsonResponse(['message' => 'Rôle mis à jour avec succès']);
    }

    #[Route('/api/roles/{id}', name: 'delete_role', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $role = $em->getRepository(Role::class)->find($id);
        if (!$role) {
            return new JsonResponse(['error' => 'Rôle non trouvé'], 404);
        }

        $em->remove($role);
        $em->flush();

        return new JsonResponse(['message' => 'Rôle supprimé avec succès']);
    }
}
