<?php
require '/var/www/private/php-BIB/vendor/autoload.php'; // Assurez-vous que l'autoload est inclus
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Fonction pour vérifier le JWT
function verifyJWT($token, $userId) {
    $res_tab=[];
    $secretKey = 'didine_canon_16'; // Remplacez par votre clé secrète

    try {
        // Décoder le token
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

        // Vérifier l'ID utilisateur
        if ($decoded->data->userId != $userId) {
            return false; // L'ID utilisateur ne correspond pas
        }

        // Vérifier si le token est expiré
        $current_time = time();
        if ($current_time > $decoded->exp) {
            return false; // Le token a expiré
        }

        // Si toutes les vérifications réussissent
        return true;
    } catch (Exception $e) {
        // Gérer les erreurs (par exemple, si le token est invalide ou mal formé)
        return false;
    }
}
?>
