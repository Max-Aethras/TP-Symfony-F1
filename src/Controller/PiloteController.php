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
use Psr\Log\LoggerInterface;

#[Route('/api/pilote')]
class PiloteController extends AbstractController
{
    #[Route('/{id}', name: 'get_pilote', methods: ['GET'])]
    public function getPilote(
        int $id,
        PiloteRepository $piloteRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $pilote = $piloteRepository->find($id);

        if (!$pilote) {
            return new JsonResponse([
                'message' => 'Pilote non trouvé pour l\'ID: ' . $id
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $jsonContent = $serializer->serialize($pilote, 'json', [
            'groups' => ['pilote:read'] 
        ]);

        return new JsonResponse($jsonContent, JsonResponse::HTTP_OK, [], true);
    }
    
    #[Route('/{id}', name: 'update_pilote', methods: ['PATCH'])]
    public function updatePilote(
        int $id,
        Request $request,
        PiloteRepository $piloteRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        LoggerInterface $logger 
    ): JsonResponse {
        $pilote = $piloteRepository->find($id);

        if (!$pilote) {
            return new JsonResponse([
                'message' => 'Pilote non trouvé pour l\'ID: ' . $id
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
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
        
        if (isset($data['statut'])) {
            $pilote->setStatut($data['statut']);
        }

        if (isset($data['points']) && is_int($data['points'])) {
            $points = max(0, $data['points']);
            $pilote->setPoints($points);
        }

        try {
            $entityManager->flush();
        } catch (\Exception $e) {
            $logger->error("Erreur Doctrine/DB: " . $e->getMessage());
            return new JsonResponse([
                'message' => 'Erreur interne lors de l\'enregistrement des données.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $jsonContent = $serializer->serialize($pilote, 'json', [
            'groups' => ['pilote:read']
        ]);

        return new JsonResponse($jsonContent, JsonResponse::HTTP_OK, [], true);
    }
}