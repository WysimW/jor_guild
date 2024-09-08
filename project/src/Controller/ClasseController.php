<?php

namespace App\Controller;

use App\Entity\Classe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClasseController extends AbstractController
{
    #[Route('/api/classes', name: 'browse_classes', methods: ['GET'])]
    public function browse(EntityManagerInterface $em): JsonResponse
    {
        // Récupérer toutes les classes de la base de données
        $classes = $em->getRepository(Classe::class)->findAll();

        // Retourner les classes sous forme de réponse JSON
        return $this->json($classes, 200, [], ['groups' => 'classe:read']);
    }
}
