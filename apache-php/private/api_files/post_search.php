<?php

/**
 * Ce script gère une requête de recherche pour une station ou une localisation.
 * 
 * Entrée:
 * - Requête POST avec un champ 'search'.
 * 
 * Processus:
 * - Vérifie le champ 'search'.
 * - Encode et envoie la recherche à une API externe.
 * - Décode la réponse JSON de l'API.
 * - Prépare un message selon la réponse.
 * - Enregistre la recherche dans la base de données.
 * 
 * Sortie:
 * - Réponse JSON avec 'lat', 'lon' et 'message'.
 * 
 * Erreurs:
 * - 400 si 'search' est manquant/Session invalide.
 * - 500 si l'API échoue ou problème serveur(la base de données).
 */

// Vérifie si le champ 'search' est défini dans les données reçues
if (!isset($data['search']) || !isset($_SESSION["id"])) {
    http_response_code(400); // Code de réponse 400 pour une mauvaise requête
    echo json_encode(["error" => "Données manquantes"]);
    exit();
}

$response = []; // Crée un tableau pour la réponse

$search = $data['search']; // Récupère la valeur du champ 'search'
$search_code = rawurlencode($search); // Encode la recherche pour l'utiliser dans une URL
$affichageMessage = ""; // Initialise le message d'affichage

$url = "http://flask-app:5000/search_station/{$search_code}"; // URL de l'API de recherche

// Utilisation de file_get_contents pour récupérer le contenu JSON
$response = file_get_contents($url);

// Vérifie si la réponse est vide ou fausse
if (!$response) {
    http_response_code(500); // Erreur 500 : Probleme du serveur
    echo json_encode(["error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.", "error_code" => "erreur code: 2", "token" => false]);
    exit();
}

// Convertir le JSON en tableau PHP
$data = json_decode($response, true);

// Vérifie si la réponse contient une erreur
if (isset($data["error"])) {
    $station_id = 0;
    $resultat = 0;
} else {
    $station_id = $data["station_id"];
    $resultat = 1;

    // Détermine le message d'affichage en fonction de la présence de station_id
    if ($station_id === null) {
        $affichageMessage = "Localisation trouvée!";
    } else {
        $affichageMessage = "Station trouvée!";
    }
    // Prépare la réponse à retourner
    $response = [
        "lat" => $data["lat"],
        "lon" => $data["lon"],
        "message" => $affichageMessage
    ];
}

$id = (int)$_SESSION["id"]; // Récupère l'ID de l'utilisateur depuis la session
$search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); // Sécurise la recherche
$search = preg_replace('/[ ]+/', ' ', $search); // Remplace les espaces multiples par un seul espace

try {
    $conn = Model::getModel(); // Obtenir la connexion à la base de données
    $conn->postSearch($id, $search, $station_id, $resultat); // Enregistre la recherche dans la base de données
} catch (Exception $e) {
    http_response_code(500); // Erreur 500 : Probleme du serveur
    echo $e->getMessage(); // Affiche le message d'erreur
    exit();
}

echo json_encode($response); // Encode la réponse en JSON et l'affiche
