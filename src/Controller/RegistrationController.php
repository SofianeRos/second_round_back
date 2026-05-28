<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private NormalizerInterface $normalizer,
    ) {}

    #[\Symfony\Component\Routing\Attribute\Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            // Validation basique
            if (empty($data['email']) || empty($data['pseudo']) || empty($data['password'])) {
                return new JsonResponse(['error' => 'Email, pseudo et mot de passe sont requis'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Vérifier si l'utilisateur existe déjà
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                return new JsonResponse(['error' => 'Cet email est déjà utilisé'], JsonResponse::HTTP_CONFLICT);
            }

            // Créer un nouvel utilisateur
            $user = new User();
            $user->setEmail($data['email']);
            $user->setPseudo($data['pseudo']);
            $user->setRoles(['ROLE_USER']);
            $user->setDateInscription(new \DateTime());

            // Hacher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);

            // Données optionnelles
            if (!empty($data['taille_cm'])) {
                $user->setTailleCm((int) $data['taille_cm']);
            }
            if (!empty($data['poids_kg'])) {
                $user->setPoidsKg((int) $data['poids_kg']);
            }
            if (!empty($data['niveau'])) {
                $user->setNiveau($data['niveau']);
            }
            if (!empty($data['type_boxe'])) {
                $user->setTypeBoxe($data['type_boxe']);
            }
            if (!empty($data['budget_max'])) {
                $user->setBudgetMax((string) $data['budget_max']);
            }

            // Persister l'utilisateur
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new JsonResponse([
                'message' => 'Inscription réussie',
                'user' => $this->normalizer->normalize($user, null, ['groups' => ['read']]),
            ], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
