<?php

// src/Controller/RaidController.php

namespace App\Controller\Api;

use App\Entity\Raid;
use App\Entity\RaidRegister;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RaidController extends AbstractController
{
    #[Route('/api/raids', name: 'browse_raids', methods: ['GET'])]
    public function browse(EntityManagerInterface $em): JsonResponse
    {
        // Récupérer uniquement les raids qui ne sont pas archivés
        $raids = $em->getRepository(Raid::class)->findBy(['isArchived' => false]);

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
        $raid->setArchived(false);
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
    public function history(Request $request, EntityManagerInterface $em): JsonResponse
    {
        // Récupérer les paramètres de requête
        $search = $request->query->get('search', '');
        $difficulty = $request->query->get('difficulty', '');
        $boss = $request->query->get('boss', '');
        $sort = $request->query->get('sort', 'date');
        $order = $request->query->get('order', 'desc');
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
    
        // Construire la requête
        $raidRepository = $em->getRepository(Raid::class);
        $queryBuilder = $raidRepository->createQueryBuilder('r');
    
        // Filtrer les raids archivés
        $queryBuilder->andWhere('r.isArchived = :archived')
            ->setParameter('archived', true);
    
        // Filtrer par difficulté
        if (!empty($difficulty)) {
            $queryBuilder->andWhere('r.mode = :difficulty')
                ->setParameter('difficulty', $difficulty);
        }
    
        // Recherche par titre ou description
        if (!empty($search)) {
            $queryBuilder->andWhere('r.title LIKE :search OR r.description LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }
    
        // Tri
        $allowedSortFields = ['date', 'title'];
        if (in_array($sort, $allowedSortFields)) {
            $queryBuilder->orderBy('r.'.$sort, $order === 'asc' ? 'ASC' : 'DESC');
        } else {
            // Tri par défaut si le paramètre 'sort' n'est pas valide
            $queryBuilder->orderBy('r.date', 'DESC');
        }
    
        // Pagination
        $queryBuilder->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
    
        // Créer le Paginator
        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query, $fetchJoinCollection = true);
    
        // Compter le nombre total de raids pour la pagination
        $totalRaidsCount = count($paginator);
    
        // Préparer les données à retourner
        $raidsData = [];
        $presentStatus = 'Présent'; // Le statut que vous souhaitez filtrer
    
        foreach ($paginator as $raid) {
            // Charger les relations nécessaires
            $em->initializeObject($raid);
            $em->initializeObject($raid->getDownBosses());
            $em->initializeObject($raid->getRaidRegisters());
    
            // Récupérer les inscriptions au raid avec le statut 'Présent'
            $inscriptions = $raid->getRaidRegisters()->filter(function($inscription) use ($presentStatus) {
                return $inscription->getStatus() === $presentStatus;
            });
    
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
    
            // Récupérer les boss tombés pour le raid
            $downedBossesData = [];
            foreach ($raid->getDownBosses() as $boss) {
                $downedBossesData[] = [
                    'id' => $boss->getId(),
                    'name' => $boss->getName(),
                    // Ajoutez d'autres propriétés du boss si nécessaire
                ];
            }
    
            // Préparer les données du raid
            $raidsData[] = [
                'id' => $raid->getId(),
                'title' => $raid->getTitle(),
                'description' => $raid->getDescription(),
                'date' => $raid->getDate()->format(\DateTime::ATOM), // Date au format ISO 8601
                'downedBosses' => $downedBossesData, // Ajout de la liste des boss tombés
                'inscriptions' => $inscriptionsData, // Liste des personnages inscrits
                'links' => [
                    'warcraftLogs' => $raid->getWlogLink(),
                    'wowAnalyzer' => $raid->getWanalyzerLink(),
                    'wipeFest' => $raid->getWipefestLink(),
                ]
            ];
        }
    
        // Retourner les données avec les informations de pagination
        return $this->json([
            'data' => $raidsData,
            'total' => $totalRaidsCount,
            'page' => $page,
            'limit' => $limit,
        ], 200, [], ['groups' => 'raid:read']);
    }
}
