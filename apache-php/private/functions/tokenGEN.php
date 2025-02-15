<?php
    require '/var/www/private/php-BIB/vendor/autoload.php';
    use Firebase\JWT\JWT;
function generateJWT($userId) {

    // Clé secrète pour signer le token
    $secretKey = 'didine_canon_16'; // Remplacez par une clé secrète sécurisée

    // Temps actuel
    $issuedAt = time();
    // Temps d'expiration (1 heure plus tard)
    $expirationTime = $issuedAt + 3600; // 3600 secondes = 1 heure

    // Payload du token sans 'iss' et 'aud'
    $payload = [
        'iat' => $issuedAt,               // Temps d'émission
        'exp' => $expirationTime,         // Temps d'expiration
        'data' => [
            'userId' => $userId,          // ID de l'utilisateur
        ]
    ];

    // Générer le token
    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    return $jwt;
}

?>