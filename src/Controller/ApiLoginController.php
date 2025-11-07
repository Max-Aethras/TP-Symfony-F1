<?php

// src/Controller/ApiLoginController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(): JsonResponse
    {
        // Le user est automatiquement disponible si l'authentification est réussie
        // grâce à la configuration de security.yaml (via le guard JWT)
        $user = $this->getUser();

        if (!$user instanceof User) {
            // Cette erreur ne devrait pas être atteinte si l'authentification est configurée correctement
            return $this->json([
                'message' => 'L\'authentification a échoué.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Si l'authentification est réussie, Symfony génère le jeton automatiquement
        // et il est ajouté dans la réponse via la configuration JWT.
        // Le code ci-dessous est optionnel car le bundle gère la réponse,
        // mais il permet de personnaliser le retour si nécessaire.

        return $this->json([
            'message' => 'Connexion réussie!',
            'email'  => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            // Le 'token' est généralement ajouté par l'événement du bundle LexikJWT,
            // mais ce contrôleur peut être simplifié pour laisser le bundle gérer la réponse.
            // Cependant, le fait de laisser cette méthode vide est ce qui fait fonctionner l'authentification POST /api/login
            // Le token sera généré et renvoyé automatiquement.
        ]);
    }
}