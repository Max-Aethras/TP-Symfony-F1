<?php

namespace App\Controller;

use App\Entity\Pilote;
use App\Repository\PiloteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface; // Ajout pour le logging d'erreurs

#[Route('/api/pilote')]
class PiloteController extends AbstractController
{
    /**
     * Route permettant de récupérer les détails d'un Pilote par son ID.
     */
    #[Route('/{id}', name: 'get_pilote', methods: ['GET'])]
    public function getPilote(
        int $id,
        PiloteRepository $piloteRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        // 1. Chercher le pilote par son ID
        $pilote = $piloteRepository->find($id);

        if (!$pilote) {
            // Code HTTP 404 si le pilote n'est pas trouvé
            return new JsonResponse([
                'message' => 'Pilote non trouvé pour l\'ID: ' . $id
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        // 2. Retourner le pilote en JSON
        $jsonContent = $serializer->serialize($pilote, 'json', [
            // Note: Si vous n'utilisez pas de groupes de sérialisation, cette option n'est pas nécessaire
            'groups' => ['pilote:read'] 
        ]);

        // Le paramètre 'true' à la fin indique que $jsonContent est déjà une chaîne JSON
        return new JsonResponse($jsonContent, JsonResponse::HTTP_OK, [], true);
    }
    
    /**
     * Route permettant de mettre à jour le statut, les points ou d'autres champs d'un Pilote par son ID.
     * Utilise la méthode PATCH pour une mise à jour partielle.
     */
    #[Route('/{id}', name: 'update_pilote', methods: ['PATCH'])]
    public function updatePilote(
        int $id,
        Request $request,
        PiloteRepository $piloteRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer, // Utilisé ici pour faciliter la conversion JSON -> Objet
        LoggerInterface $logger // Ajout de LoggerInterface pour les logs
    ): JsonResponse {
        // 1. Chercher le pilote par son ID
        $pilote = $piloteRepository->find($id);

        if (!$pilote) {
            // Code HTTP 404 si le pilote n'est pas trouvé
            return new JsonResponse([
                'message' => 'Pilote non trouvé pour l\'ID: ' . $id
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        // 2. Récupérer et décoder les données JSON (ex: {"statut": "reserviste", "points": 10})
        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Erreur de parsing JSON
                return new JsonResponse([
                    'message' => 'Données JSON invalides.'
                ], JsonResponse::HTTP_BAD_REQUEST);
            }

        } catch (\Exception $e) {
            $logger->error("Erreur de décodage JSON dans updatePilote: " . $e->getMessage());
            return new JsonResponse([
                'message' => 'Requête invalide ou données manquantes.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        
        // 3. Mise à jour des propriétés (PATCH)
        // Mise à jour du statut
        if (isset($data['statut'])) {
            $pilote->setStatut($data['statut']);
        }

        // Mise à jour des points de licence
        if (isset($data['points']) && is_int($data['points'])) {
            // Contrainte: les points ne peuvent pas être négatifs.
            $points = max(0, $data['points']);
            $pilote->setPoints($points);
        }

        // 4. Enregistrement des changements
        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            $logger->error("Erreur Doctrine/DB: " . $e->getMessage());
            // L'erreur 500 indique une erreur côté serveur lors de l'enregistrement
            return new JsonResponse([
                'message' => 'Erreur interne lors de l\'enregistrement des données.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        // 5. Retourner le pilote mis à jour
        // Normaliser l'objet Pilote en JSON pour la réponse
        $jsonContent = $serializer->serialize($pilote, 'json', [
            // Pour l'affichage, il est bon d'inclure l'écurie
            'groups' => ['pilote:read']
        ]);

        return new JsonResponse($jsonContent, JsonResponse::HTTP_OK, [], true);
    }
}