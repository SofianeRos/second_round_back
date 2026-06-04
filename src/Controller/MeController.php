<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
}
