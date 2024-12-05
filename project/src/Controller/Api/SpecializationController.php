<?php

namespace App\Controller\Api;

use App\Entity\Character;
use App\Entity\Specialization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SpecializationController extends AbstractController
{
    #[Route('/api/characters/{id}/specializations', name: 'character_specializations', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function getSpecializations(int $id, EntityManagerInterface $em): JsonResponse
    {
        // Récupérer le personnage par ID
        $character = $em->getRepository(Character::class)->find($id);

        if (!$character) {
            return new JsonResponse(['error' => 'Personnage non trouvé'], 404);
        }

        // Récupérer la classe du personnage
        $class = $character->getClasse();

        if (!$class) {
            return new JsonResponse(['error' => 'Classe non associée au personnage'], 404);
        }

        // Récupérer les spécialisations associées à cette classe
        $specializations = $em->getRepository(Specialization::class)->findBy(['classe' => $class]);

        // Retourner les spécialisations en JSON
        return $this->json($specializations, 200, [], ['groups' => 'specialization:read']);
    }
}
