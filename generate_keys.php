<?php
$config = [
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
];

putenv("OPENSSL_CONF=");

$res = openssl_pkey_new($config);
if ($res === false) {
    echo "Erreur OpenSSL: " . openssl_error_string();
    exit(1);
}

openssl_pkey_export($res, $privKey);
$pubKeyDetails = openssl_pkey_get_details($res);
$pubKey = $pubKeyDetails['key'];

$jwtDir = 'config/jwt';
@mkdir($jwtDir, 0755, true);

file_put_contents($jwtDir . '/private.pem', $privKey);
file_put_contents($jwtDir . '/public.pem', $pubKey);

echo "✓ Clés JWT générées avec succès!\n";
echo "✓ Private key: " . realpath($jwtDir . '/private.pem') . "\n";
echo "✓ Public key: " . realpath($jwtDir . '/public.pem') . "\n";
