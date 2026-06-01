<?php

use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

echo "=== VÉRIFICATION JWT ===\n\n";

// 1. Vérifier les clés JWT
echo "1. Vérification des clés JWT:\n";
echo "   ---\n";

$privateKeyPath = $_ENV['JWT_SECRET_KEY'] ?? null;
$publicKeyPath = $_ENV['JWT_PUBLIC_KEY'] ?? null;

if (!$privateKeyPath || !$publicKeyPath) {
    echo "   ❌ Clés non configurées dans .env\n";
    exit(1);
}

// Remplacer %kernel.project_dir% par le chemin réel
$projectDir = __DIR__;
$privateKeyPath = str_replace('%kernel.project_dir%', $projectDir, $privateKeyPath);
$publicKeyPath = str_replace('%kernel.project_dir%', $projectDir, $publicKeyPath);

echo "   Private key: " . $privateKeyPath . "\n";
echo "   Public key:  " . $publicKeyPath . "\n";

if (!file_exists($privateKeyPath)) {
    echo "   ❌ Clé privée non trouvée\n";
    exit(1);
}

if (!file_exists($publicKeyPath)) {
    echo "   ❌ Clé publique non trouvée\n";
    exit(1);
}

echo "   ✓ Les deux clés existent\n";

// 2. Vérifier que les clés sont valides
echo "\n2. Vérification de la validité des clés:\n";
echo "   ---\n";

$privateKeyContent = file_get_contents($privateKeyPath);
$publicKeyContent = file_get_contents($publicKeyPath);

if (strpos($privateKeyContent, '-----BEGIN') === false || strpos($privateKeyContent, 'PRIVATE KEY-----') === false) {
    echo "   ❌ Format de clé privée invalide\n";
    exit(1);
}

if (strpos($publicKeyContent, '-----BEGIN PUBLIC KEY-----') === false) {
    echo "   ❌ Format de clé publique invalide\n";
    exit(1);
}

echo "   ✓ Les clés ont le bon format PEM\n";

// 3. Tester avec OpenSSL
echo "\n3. Génération et vérification de test JWT:\n";
echo "   ---\n";

try {
    $passphrase = $_ENV['JWT_PASSPHRASE'] ?? '';

    // Charger les clés en tant que ressources OpenSSL
    $privateKey = openssl_pkey_get_private($privateKeyContent, $passphrase);
    $publicKey = openssl_pkey_get_public($publicKeyContent);

    if ($privateKey === false) {
        echo "   ❌ Impossible de charger la clé privée: " . openssl_error_string() . "\n";
        exit(1);
    }

    if ($publicKey === false) {
        echo "   ❌ Impossible de charger la clé publique: " . openssl_error_string() . "\n";
        exit(1);
    }

    echo "   ✓ Clés chargées avec succès\n";

    // Créer un token de test simple
    $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
    $payload = base64_encode(json_encode([
        'user_id' => 1,
        'email' => 'test@example.com',
        'iat' => time(),
        'exp' => time() + 3600
    ]));

    // Nettoyer les padding '='
    $header = rtrim($header, '=');
    $payload = rtrim($payload, '=');

    $message = $header . '.' . $payload;

    // Signer avec la clé privée
    $signature = '';
    $success = openssl_sign($message, $signature, $privateKey, 'sha256WithRSAEncryption');

    if (!$success) {
        echo "   ❌ Erreur lors de la signature: " . openssl_error_string() . "\n";
        exit(1);
    }

    $signature = base64_encode($signature);
    $signature = rtrim($signature, '=');

    $token = $message . '.' . $signature;

    echo "   ✓ Token généré avec succès\n";
    echo "   Token: " . substr($token, 0, 60) . "...\n\n";

    // Vérifier le token
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        echo "   ❌ Format de token invalide\n";
        exit(1);
    }

    $message = $parts[0] . '.' . $parts[1];
    // Ajouter le padding nécessaire
    $paddedSignature = $parts[2] . str_repeat('=', 4 - (strlen($parts[2]) % 4));
    $signature = base64_decode($paddedSignature);

    $verified = openssl_verify($message, $signature, $publicKey, 'sha256WithRSAEncryption');

    if ($verified === 1) {
        echo "   ✓ Signature du token vérifiée avec succès\n";
    } elseif ($verified === 0) {
        echo "   ❌ Token invalide (signature ne correspond pas)\n";
        exit(1);
    } else {
        echo "   ❌ Erreur lors de la vérification: " . openssl_error_string() . "\n";
        exit(1);
    }

    openssl_free_key($privateKey);
    openssl_free_key($publicKey);
} catch (\Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}

// 4. Résumé
echo "\n=== RÉSUMÉ ===\n";
echo "✓ Configuration JWT correcte\n";
echo "✓ Clés JWT valides et lisibles\n";
echo "✓ Clés JWT chargées correctement (passphrase OK)\n";
echo "✓ Génération de tokens RS256 fonctionnelle\n";
echo "✓ Vérification de tokens RS256 fonctionnelle\n";
echo "\nℹ️  Format: RS256 (RSA SHA256)\n";
echo "ℹ️  Clé privée: ENCRYPTED PRIVATE KEY (format PKCS#8)\n";
echo "\nLa configuration JWT est correcte et prête à être utilisée.\n";
