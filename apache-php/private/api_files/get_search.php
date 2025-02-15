<?php

/**
 * Ce script PHP gère la récupération et l'affichage de l'historique de recherche d'un client.
 * 
 * Entrées :
 * - $_SESSION["id"] : Identifiant du client stocké dans la session.
 * 
 * Sortie:
 *  - Réponse JSON avec du contenu HTML de l'historique de recherche.
 * 
 * Erreurs:
 * - 400 Session invalide si l'ID du client n'est pas présent dans la session.
 * - 500 Problème du serveur lors de la connexion à la base de données ou de la récupération des données.
 */

// Vérifie si la session est valide
if (!isset($_SESSION["id"])) {
    http_response_code(400); // Code de réponse 400 pour une mauvaise requête
    echo json_encode(["error" => "Session invalide"]);
    exit();
}

$response = []; // Crée un tableau pour la réponse

// Récupère l'identifiant du client depuis la session
$id_client = (int)$_SESSION["id"];

try {
    // Connexion au modèle et récupération des résultats de recherche
    $conn = Model::getModel();
    $response = $conn->getSearch($id_client);
} catch (Exception $e) {
    // En cas d'erreur, renvoie une erreur 500 et affiche le message d'erreur
    http_response_code(500); // Erreur 500 : Problème du serveur
    echo $e->getMessage();
    exit();
}

// Initialiser $searchContent à une chaîne vide
$searchContent = "";

if (!empty($response)) {
    // Parcourt chaque résultat de recherche
    foreach ($response as $val) {
        // Définition des icônes par défaut
        $couleurSearch = "bi bi-x-circle-fill me-2 text-danger"; // Par défaut en rouge (échec)
        $typeSearch = ""; // Vide par défaut

        // Si le résultat est positif (1), on change la couleur et le type d'icône
        if ($val["resultat"] === 1) {
            $couleurSearch = "bi bi-check-circle-fill me-2 text-success"; // Vert pour succès
            $typeSearch = "bi bi-geo-alt-fill icon-large"; // Icône de localisation

            // Si la recherche est vide donc il a chercher une station, utiliser la station et changer l'icône
            if (!isset($val["recherche"])) {
                $val["recherche"] = $val["station"]; // Utilise le nom de la station car il a cherché une station
                $typeSearch = "bi bi-bicycle icon-bike"; // Icône de vélo
            }
        }

        // Construit le contenu HTML de l'historique de recherche
        $searchContent .= "
        <div class='card mb-3 historique-item'>
            <div class='card-body d-flex justify-content-between align-items-center'>
                <div>
                    <h5 class='card-title'>
                        {$val['recherche']}
                        <i class='{$couleurSearch}'></i>
                        <i class='{$typeSearch}'></i>
                    </h5>
                    <p class='card-text'>Date de Recherche : {$val['created_at']}</p>
                </div>
                <button class='btn btn-danger btn-sm remove-btn' data-id='{$val['id']}' onclick='removeSearch(this)'>
                    Supprimer
                </button>
            </div>
        </div>";
    }

    // Ajouter un titre à l'historique des recherches
    $searchContent = "<h3 class='section-title' style='margin-bottom: 20px;'>Historique de recherche</h3>" . $searchContent;
} else {
    // Si aucun résultat, afficher un message indiquant qu'il n'y a pas d'historique
    $searchContent = "<h3 class='section-title' style='margin-bottom: 20px;'>Historique de recherche</h3><p style='text-align: center;'>Aucun historique de recherche disponible.</p>";
}

// Encode la réponse en JSON et l'affiche
echo json_encode(["resultat" => $searchContent]);
?>
