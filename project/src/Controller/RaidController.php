<?php

// src/Controller/RaidController.php

namespace App\Controller;

use App\Entity\Raid;
use App\Entity\RaidRegister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RaidController extends AbstractController
{
    #[Route('/api/raids', name: 'browse_raids', methods: ['GET'])]
    public function browse(EntityManagerInterface $em): JsonResponse
    {
        $raids = $em->getRepository(Raid::class)->findAll();

        // Utiliser le groupe de sérialisation 'raid:read'
        return $this->json($raids, 200, [], ['groups' => 'raid:read']);
    }

    #[Route('/api/raid/{id}/details', name: 'raid_details', methods: ['GET'])]
public function raidDetails(int $id, EntityManagerInterface $em): JsonResponse
{
    $raid = $em->getRepository(Raid::class)->find($id);
    if (!$raid) {
        return new JsonResponse(['error' => 'Raid non trouvé'], 404);
    }

    // Récupérer les inscriptions au raid (triées par rôle)
    $inscriptions = $em->getRepository(RaidRegister::class)->findBy(['raid' => $raid]);

    return $this->json([
        'title' => $raid->getTitle(),
        'description' => $raid->getDescription(),
        'inscriptions' => $inscriptions,
    ], 200, [], ['groups' => 'raid:read']);
}

    #[Route('/api/raids/{id}', name: 'read_raid', methods: ['GET'])]
    public function read(int $id, EntityManagerInterface $em): JsonResponse
    {
        $raid = $em->getRepository(Raid::class)->find($id);
        if (!$raid) {
            return new JsonResponse(['error' => 'Raid non trouvé'], 404);
        }

        return $this->json($raid);
    }

    #[Route('/api/raids', name: 'add_raid', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $raid = new Raid();
        $raid->setTitle($data['title']);
        $raid->setDescription($data['description']);
        $raid->setDate(new \DateTime($data['date']));
        $raid->setCapacity($data['capacity']);

        $em->persist($raid);
        $em->flush();

        return new JsonResponse(['message' => 'Raid créé avec succès'], 201);
    }

    #[Route('/api/raids/{id}', name: 'edit_raid', methods: ['PUT'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $raid = $em->getRepository(Raid::class)->find($id);
        if (!$raid) {
            return new JsonResponse(['error' => 'Raid non trouvé'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $raid->setTitle($data['title']);
        $raid->setDescription($data['description']);
        $raid->setDate(new \DateTime($data['date']));
        $raid->setCapacity($data['capacity']);

        $em->flush();

        return new JsonResponse(['message' => 'Raid mis à jour avec succès']);
    }

    #[Route('/api/raids/{id}', name: 'delete_raid', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $raid = $em->getRepository(Raid::class)->find($id);
        if (!$raid) {
            return new JsonResponse(['error' => 'Raid non trouvé'], 404);
        }

        $em->remove($raid);
        $em->flush();

        return new JsonResponse(['message' => 'Raid supprimé avec succès']);
    }
}
