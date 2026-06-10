<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commande;
use App\Entity\Statut;
use App\Entity\Messagerie;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    #[Route('/api/create-checkout-session', name: 'app_create_checkout_session', methods: ['POST'])]
    public function createCheckoutSession(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        $articleId = $data['articleId'] ?? null;
        $prix = $data['prix'] ?? null;

        if (!$articleId || !$prix) {
            return new JsonResponse(['error' => 'Paramètres invalides'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $article = $entityManager->getRepository(Article::class)->find($articleId);
        if (!$article) {
            return new JsonResponse(['error' => 'Article non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Vérifier que le statut de l'article est "En vente"
        if ($article->getStatut() && $article->getStatut()->getLibelle() !== 'En vente') {
            return new JsonResponse(['error' => 'Cet article n\'est plus disponible'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Récupérer la clé secrète Stripe depuis les variables d'environnement
        $stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'] ?? null;
        if (!$stripeSecretKey) {
            return new JsonResponse(['error' => 'Configuration Stripe manquante'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            Stripe::setApiKey($stripeSecretKey);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Achat article : ' . $article->getCategorie() . ' ' . $article->getMarque(),
                            'description' => 'Achat sécurisé sur Second Round',
                        ],
                        'unit_amount' => (int) (round($prix * 100)), // en centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://localhost:5173/payment/success?session_id={CHECKOUT_SESSION_ID}&article_id=' . $article->getId(),
                'cancel_url' => 'http://localhost:5173/payment/cancel',
                'metadata' => [
                    'article_id' => $article->getId(),
                    'acheteur_id' => $user->getId(),
                    'prix_final' => $prix,
                ],
            ]);

            return new JsonResponse([
                'id' => $session->id,
                'url' => $session->url,
            ]);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Invalid API Key') || str_contains($e->getMessage(), 'mock key detected')) {
                // URL de simulation de Stripe Checkout en local
                $mockSessionId = 'mock_session_' . $article->getId() . '_' . $user->getId() . '_' . $prix . '_' . uniqid();
                $mockUrl = 'http://localhost:5173/payment/success?session_id=' . $mockSessionId . '&article_id=' . $article->getId();
                return new JsonResponse([
                    'id' => $mockSessionId,
                    'url' => $mockUrl,
                ]);
            }
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/payment/confirm', name: 'app_payment_confirm', methods: ['POST'])]
    public function confirmPayment(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Non authentifié'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        $sessionId = $data['session_id'] ?? null;

        if (!$sessionId) {
            return new JsonResponse(['error' => 'Session ID manquant'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'] ?? null;
        if (!$stripeSecretKey) {
            return new JsonResponse(['error' => 'Configuration Stripe manquante'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            if (str_starts_with($sessionId, 'mock_session_')) {
                $parts = explode('_', $sessionId);
                // parts: [0 => 'mock', 1 => 'session', 2 => articleId, 3 => acheteurId, 4 => prixFinal, 5 => uniqid]
                $articleId = $parts[2] ?? null;
                $acheteurId = $parts[3] ?? null;
                $prixFinal = $parts[4] ?? null;
            } else {
                Stripe::setApiKey($stripeSecretKey);
                $session = Session::retrieve($sessionId);
                if ($session->payment_status !== 'paid') {
                    return new JsonResponse(['error' => 'Paiement non finalisé'], JsonResponse::HTTP_BAD_REQUEST);
                }

                // Récupérer les métadonnées de la session
                $articleId = $session->metadata->article_id ?? null;
                $acheteurId = $session->metadata->acheteur_id ?? null;
                $prixFinal = $session->metadata->prix_final ?? null;
            }

            if (!$articleId || !$acheteurId || !$prixFinal) {
                return new JsonResponse(['error' => 'Données de session invalides'], JsonResponse::HTTP_BAD_REQUEST);
            }

            // Vérifier si cette commande a déjà été créée (pour éviter les doublons)
            $existingOrder = $entityManager->getRepository(Commande::class)->findOneBy(['article' => $articleId]);
            if ($existingOrder) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Paiement déjà confirmé',
                    'orderId' => $existingOrder->getId()
                ]);
            }

            $article = $entityManager->getRepository(Article::class)->find($articleId);
            $acheteur = $entityManager->getRepository(User::class)->find($acheteurId);

            if (!$article || !$acheteur) {
                return new JsonResponse(['error' => 'Données introuvables'], JsonResponse::HTTP_NOT_FOUND);
            }

            // 1. Marquer l'article comme Vendu
            $statutVendu = $entityManager->getRepository(Statut::class)->findOneBy(['libelle' => 'Vendu']);
            if ($statutVendu) {
                $article->setStatut($statutVendu);
            }

            // 2. Créer la Commande
            $commande = new Commande();
            $commande->setArticle($article);
            $commande->setAcheteur($acheteur);
            $commande->setPrixFinal($prixFinal);
            $commande->setFraisPort('0.00'); // frais de port gratuits
            $commande->setStatutLivraison('en attente');
            $commande->setDateCommande(new \DateTime());

            $entityManager->persist($commande);

            // 3. Envoyer un message de confirmation dans la messagerie
            $msg = new Messagerie();
            $msg->setContenu("🎉 Paiement de " . $prixFinal . " € effectué avec succès ! Cet article est vendu et vous devez organiser la livraison.");
            $msg->setEstOffre(false);
            $msg->setExpediteur($acheteur);
            $msg->setDestinataire($article->getVendeur());
            $msg->setArticle($article);
            $msg->setDateEnvoie(new \DateTime());

            $entityManager->persist($msg);
            $entityManager->flush();

            return new JsonResponse([
                'success' => true,
                'message' => 'Paiement confirmé et commande créée avec succès',
                'orderId' => $commande->getId(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
