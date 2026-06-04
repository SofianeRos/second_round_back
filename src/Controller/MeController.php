<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MeController extends AbstractController
{
    public function __construct(
        private NormalizerInterface $normalizer,
    ) {}

    #[Route('/api/me', name: 'app_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(
            $this->normalizer->normalize($user, null, ['groups' => ['read']])
        );
    }

    #[Route('/api/me/avatar', name: 'app_me_avatar', methods: ['POST'])]
    public function uploadAvatar(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $file = $request->files->get('file');
        if (!$file) {
            return new JsonResponse(['error' => 'Aucun fichier fourni'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Valider le fichier (type MIME)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return new JsonResponse(['error' => 'Format d\'image invalide (JPEG, PNG ou WEBP uniquement)'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Nom unique pour le fichier
        $filename = uniqid() . '.' . $file->guessExtension();

        // Déplacer le fichier dans le dossier public/images/photos
        $targetDir = $this->getParameter('kernel.project_dir') . '/public/images/photos';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        try {
            $file->move($targetDir, $filename);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Impossible d\'enregistrer le fichier'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Supprimer l'ancienne photo si elle existe et si elle est locale
        $oldPhoto = $user->getPhotoProfil();
        if ($oldPhoto && strpos($oldPhoto, 'http') === false) {
            $oldFilePath = $targetDir . '/' . $oldPhoto;
            if (file_exists($oldFilePath)) {
                @unlink($oldFilePath);
            }
        }

        // Mettre à jour l'utilisateur
        $user->setPhotoProfil($filename);
        $entityManager->flush();

        return new JsonResponse(
            $this->normalizer->normalize($user, null, ['groups' => ['read']])
        );
    }
}
