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

        // Préparer les données avec les personnages inscrits
        $raidsData = [];

        foreach ($raids as $raid) {
            $inscriptions = $raid->getRaidRegisters(); // Récupérer les inscriptions au raid

            $registeredCharacters = [];

            foreach ($inscriptions as $inscription) {
                $character = $inscription->getRegistredCharacter();
                $registeredCharacters[] = [
                    'id' => $character->getId(),
                    'name' => $character->getName(),
                    'user' => [
                        'id' => $character->getUser()->getId(),
                        'username' => $character->getUser()->getEmail()
                    ]
                ];
            }

            $raidsData[] = [
                'id' => $raid->getId(),
                'title' => $raid->getTitle(),
                'description' => $raid->getDescription(),
                'date' => $raid->getDate(),
                'registeredCharacters' => $registeredCharacters, // Inclure les personnages inscrits
            ];
        }

        return $this->json($raidsData, 200, [], ['groups' => 'raid:read']);
    }

    #[Route('/api/raid/{id}/details', name: 'raid_details', methods: ['GET'])]
    public function raidDetails(int $id, EntityManagerInterface $em): JsonResponse
    {
        // Récupérer le raid par son ID
        $raid = $em->getRepository(Raid::class)->find($id);
        if (!$raid) {
            return new JsonResponse(['error' => 'Raid non trouvé'], 404);
        }

        $presentStatus = 'Présent'; // Le statut que vous souhaitez filtrer

        // Récupérer uniquement les inscriptions pour le raid avec le statut 'Présent'
        $inscriptions = $em->getRepository(RaidRegister::class)->findBy([
            'raid' => $raid,
            'status' => $presentStatus
        ]);


        // Préparer un tableau contenant les informations complètes sur les inscriptions
        $inscriptionsData = [];
        foreach ($inscriptions as $inscription) {
            $character = $inscription->getRegistredCharacter();

            // Gérer les spécialisations liées à l'inscription (ManyToMany)
            $specializationsData = [];
            foreach ($inscription->getRegistredSpecialization() as $specialization) {
                $specializationsData[] = [
                    'id' => $specialization->getId(),
                    'name' => $specialization->getName(),
                    'role' => $specialization->getSpeRole(),
                ];
            }

            // Ajouter les données de l'inscription à l'ensemble
            $inscriptionsData[] = [
                'id' => $inscription->getId(),
                'status' => $inscription->getStatus(),
                'registredCharacter' => [
                    'id' => $character->getId(),
                    'name' => $character->getName(),
                    'classe' => [
                        'id' => $character->getClasse()->getId(),
                        'name' => $character->getClasse()->getName(),
                    ],
                    'specializations' => $specializationsData, // Ajout des spécialisations
                ],
                'registeredDate' => $inscription->getRegisteredDate()->format('d/m/Y H:i'),
            ];
        }

        // Renvoyer les détails du raid avec les inscriptions complètes
        return $this->json([
            'title' => $raid->getTitle(),
            'description' => $raid->getDescription(),
            'mode' => $raid->getMode(),
            'date' => $raid->getDate()->format(\DateTime::ATOM), // Envoyer la date brute au format ISO 8601
            'inscriptions' => $inscriptionsData,
        ], 200, [], ['groups' => 'raid:read']);
    }

    #[Route('/api/raid/{id}/details/absent', name: 'raid_details_absent', methods: ['GET'])]
    public function raidAbsents(int $id, EntityManagerInterface $em): JsonResponse
    {
        // Récupérer le raid par son ID
        $raid = $em->getRepository(Raid::class)->find($id);
        if (!$raid) {
            return new JsonResponse(['error' => 'Raid non trouvé'], 404);
        }

        $presentStatus = ['Absent', 'Incertain']; // Le statut que vous souhaitez filtrer

        // Récupérer uniquement les inscriptions pour le raid avec le statut 'Présent'
        $inscriptions = $em->getRepository(RaidRegister::class)->findBy([
            'raid' => $raid,
            'status' => $presentStatus
        ]);


        // Préparer un tableau contenant les informations complètes sur les inscriptions
        $inscriptionsData = [];
        foreach ($inscriptions as $inscription) {
            $character = $inscription->getRegistredCharacter();

            // Gérer les spécialisations liées à l'inscription (ManyToMany)
            $specializationsData = [];
            foreach ($inscription->getRegistredSpecialization() as $specialization) {
                $specializationsData[] = [
                    'id' => $specialization->getId(),
                    'name' => $specialization->getName(),
                    'role' => $specialization->getSpeRole(),
                ];
            }

            // Ajouter les données de l'inscription à l'ensemble
            $inscriptionsData[] = [
                'id' => $inscription->getId(),
                'status' => $inscription->getStatus(),
                'registredCharacter' => [
                    'id' => $character->getId(),
                    'name' => $character->getName(),
                    'classe' => [
                        'id' => $character->getClasse()->getId(),
                        'name' => $character->getClasse()->getName(),
                    ],
                    'specializations' => $specializationsData, // Ajout des spécialisations
                ],
                'registeredDate' => $inscription->getRegisteredDate()->format('d/m/Y H:i'),
            ];
        }

        // Renvoyer les détails du raid avec les inscriptions complètes
        return $this->json([
            'title' => $raid->getTitle(),
            'description' => $raid->getDescription(),
            'mode' => $raid->getMode(),
            'date' => $raid->getDate()->format(\DateTime::ATOM), // Envoyer la date brute au format ISO 8601
            'inscriptions' => $inscriptionsData,
        ], 200, [], ['groups' => 'raid:read']);
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

    #[Route('/api/raids/history', name: 'raid_history', methods: ['GET'])]
    public function history(EntityManagerInterface $em): JsonResponse
    {
        // Récupérer les raids archivés (avec isArchived à true)
        //$raids = $em->getRepository(Raid::class)->findBy(['isArchived' => true]);
        $raids = $em->getRepository(Raid::class)->findAll();


        $raidsData = [];
        $presentStatus = 'Présent'; // Le statut que vous souhaitez filtrer
    
        foreach ($raids as $raid) {
            $inscriptions = $em->getRepository(RaidRegister::class)->findBy([
                'raid' => $raid,
                'status' => $presentStatus
            ]);            
            $inscriptionsData = [];
            foreach ($inscriptions as $inscription) {
                $character = $inscription->getRegistredCharacter();
    
                // Gérer les spécialisations liées à l'inscription (ManyToMany)
                $specializationsData = [];
                foreach ($inscription->getRegistredSpecialization() as $specialization) {
                    $specializationsData[] = [
                        'id' => $specialization->getId(),
                        'name' => $specialization->getName(),
                        'role' => $specialization->getSpeRole(),
                    ];
                }
    
                // Ajouter les données de l'inscription à l'ensemble
            $inscriptionsData[] = [
                'id' => $inscription->getId(),
                'status' => $inscription->getStatus(),
                'registredCharacter' => [
                    'id' => $character->getId(),
                    'name' => $character->getName(),
                    'classe' => [
                        'id' => $character->getClasse()->getId(),
                        'name' => $character->getClasse()->getName(),
                    ],
                    'specializations' => $specializationsData, // Ajout des spécialisations
                ],
                'registeredDate' => $inscription->getRegisteredDate()->format('d/m/Y H:i'),
            ];
            }
    
            // Préparer les données du raid (ajouter bossesDown, logsLink, etc. si disponible)
            $raidsData[] = [
                'id' => $raid->getId(),
                'title' => $raid->getTitle(),
                'description' => $raid->getDescription(),
                'date' => $raid->getDate()->format(\DateTime::ATOM), // Date au format ISO 8601
                //'bossesDown' => $raid->getBossesDown() ?: [], // Ajouter la liste des boss tombés s'ils existent
                //'logsLink' => $raid->getLogsLink() ?: null, // Ajouter un lien vers les logs du raid s'il existe
                'inscriptions' => $inscriptionsData, // Liste des personnages inscrits
            ];
        }
    
        return $this->json($raidsData, 200, [], ['groups' => 'raid:read']);
    }
    
}
