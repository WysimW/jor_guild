<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des données (email, mot de passe)
        if (empty($data['email']) || empty($data['password'])) {
            return new JsonResponse(['error' => 'Email et mot de passe sont obligatoires.'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Vérifier si l'utilisateur existe déjà
        $userExists = $em->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($userExists) {
            return new JsonResponse(['error' => 'Un utilisateur avec cet email existe déjà.'], JsonResponse::HTTP_CONFLICT);
        }

        // Créer un nouvel utilisateur
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPseudo($data['pseudo']);

        
        // Encodage du mot de passe avec UserPasswordHasherInterface
        $encodedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($encodedPassword);

        // Ajouter des rôles (facultatif, par défaut ROLE_USER)
        $user->setRoles(['ROLE_USER']);

        // Sauvegarder l'utilisateur dans la base de données
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'Utilisateur créé avec succès !'], JsonResponse::HTTP_CREATED);
    }
}
