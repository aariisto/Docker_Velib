<?php

/*
 * Ce script traite les reservation de vélos en vérifiant les données d'entrée, 
 * en les nettoyant pour éviter les attaques XSS, et en insérant la resaervation 
 * dans la base de données.
 * 
 * Entrée attendue (via POST):
 * - confirmationID (string): L'identifiant de confirmation de la reservation.
 * - station_id (int): L'identifiant de la station où la reservation est passée.
 * - type (string): Le type de vélo commandé.
 * - $_SESSION["id"] (int): L'identifiant de l'utilisateur récupéré depuis la session.
 * 
 * Sortie:
 * - Réponse JSON avec un message de succes.
 * 
 * Erreurs:
 * - 400 si les données nécessaires sont manquantes/Session invalide.
 * - 500 si une erreur s'est produite lors de l'insertion de la reservation dans la base de données.
 * 
 * Sortie:
 * - Réponse JSON avec un message de succes.
 */

$response = []; // Crée un tableau pour la réponse

// Vérifie si les données nécessaires sont présentes
if (!isset($data['confirmationID']) || !isset($data['station_id']) || !isset($data['type']) || !isset($_SESSION["id"])) {
    http_response_code(400); // Code de réponse 400 pour une mauvaise requête
    echo json_encode(["error" => "Données manquantes"]);
    exit();
}

// Convertit les caractères spéciaux en entités HTML pour éviter les attaques XSS
$confirmationID = htmlspecialchars($data['confirmationID'], ENT_QUOTES, 'UTF-8');
$station_id = (int)$data['station_id']; // Convertit l'ID de la station en entier
$type = htmlspecialchars($data['type'], ENT_QUOTES, 'UTF-8'); // Convertit les caractères spéciaux en entités HTML pour le type
$id = (int)$_SESSION["id"]; // Récupère l'ID de l'utilisateur depuis la session et le convertit en entier

// Détermine le type de velo
switch ($type) {
    case "electric":
        $type = 1; // Type 1 pour les reservations électriques
        break;
    case "mechanical":
        $type = 2; // Type 2 pour les reservations mécaniques
        break;
    default:
        $type = 3; // Type par défaut pour les autres reservations
        break;
}

try {
    // Obtenir la connexion à la base de données
    $conn = Model::getModel();
    // Appelle la méthode postOrder pour insérer la reservation dans la base de données
    $response = $conn->postOrder($confirmationID, $type, $id, $station_id);
} catch (Exception $e) {
    // En cas d'erreur, renvoie une réponse 500 (erreur serveur) et affiche le message d'erreur
    http_response_code(500);
    echo $e->getMessage();
    exit();
}

// Encode la réponse en JSON et l'affiche
echo json_encode($response);

?>
