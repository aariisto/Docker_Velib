<?php
/**
 * Ce script supprime une recherche spécifique de la base de données.
 * 
 * Entrée:
 * - Les données reçues doivent contenir un identifiant de recherche sous la clé "id_search".
 * 
 * Sortie:
 * - Réponse JSON avec un message de succes.
 * 
 * Erreurs:
 * - 400 Si l'identifiant de recherche n'est pas fourni.
 * - 500 Problème du serveur lors de la connexion à la base de données.
 */

$response = []; // Crée un tableau pour la réponse

// Vérifie si l'identifiant de la recherche est présent dans les données reçues
if (!isset($data["id_search"])) {
    http_response_code(400); // Code de réponse 400 pour une mauvaise requête
    echo json_encode(["error" => "Session invalide"]);
    exit();
}

// Récupère l'identifiant de la recherche à supprimer depuis les données reçues
$id_search = (int)$data["id_search"];

try {
    // Obtient une instance du modèle de connexion à la base de données
    $conn = Model::getModel();
    // Supprime la recherche avec l'identifiant spécifié et stocke la réponse
    $response = $conn->removeSearch($id_search);
} catch (Exception $e) {
    http_response_code(500); // Erreur 500 : Problème du serveur
    echo $e->getMessage(); // Affiche le message d'erreur
    exit(); // Arrête l'exécution du script
}

// Encode la réponse en JSON et l'affiche
echo json_encode($response);

?>
