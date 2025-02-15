<?php

/**
 * Ce script PHP gère la récupération et l'affichage de l'historique de reservation d'un client.
 * 
 * Entrée:
 * - $_SESSION["id"] : Identifiant du client stocké dans la session.
 * 
 * Sortie:
 * - Réponse JSON avec du contenu HTML de l'historique de recherche.
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

$id_client = (int)$_SESSION["id"]; // Récupère l'ID du client depuis la session

try {
    $conn = Model::getModel(); // Obtenir la connexion à la base de données
    $response = $conn->getOrder($id_client);  // Récupère les commandes ou réservations du client
} catch (Exception $e) {
    http_response_code(500); // Erreur 500 : Problème du serveur
    echo $e->getMessage(); // Affiche le message d'erreur
    exit(); // Arrête l'exécution du script
}

$reservationContent = "";  // Initialiser le contenu des réservations

// Vérifier si la réponse contient des données
if (!empty($response)) {
    foreach ($response as $item) {
        // Déterminer l'icône en fonction de id_velo
        if ($item['id_velo'] == 1) {
            $veloIcon = "<i class='bi bi-battery-charging' title='Vélo Électrique'></i>"; // Icône vélo électrique
        } elseif ($item['id_velo'] == 2) {
            $veloIcon = "<i class='bi bi-bicycle' title='Vélo Mécanique'></i>"; // Icône vélo mécanique
        } else {
            $veloIcon = "<i class='bi bi-exclamation-triangle text-danger' title='Erreur'></i>"; // Icône d'erreur
        }

        // Génération du contenu de la réservation
        $reservationContent = "
            <div class='card mb-3 historique-item'>
                <div class='card-body d-flex justify-content-between align-items-center'>
                    <div>
                        <h5 class='card-title'> {$item['station']} {$veloIcon}</h5>
                        <p class='card-text'>Date de Réservation : {$item['create_time']}</p>
                    </div>
                    <button class='btn btn-success btn-sm remove-btn' confirmationID='{$item['confirmationID']}' onclick='showQR(this)'>Afficher le QR Code</button>
                </div>
            </div>" . $reservationContent; // Ajouter la nouvelle réservation au début du contenu
    }

    // Ajouter un titre au contenu des réservations
    $reservationContent = "<h3 class='section-title' style='margin-bottom: 20px;'>Historique de réservation</h3>" . $reservationContent;
} else {
    // Si aucune réservation n'est trouvée
    $reservationContent = "<h3 class='section-title' style='margin-bottom: 20px;'>Historique de réservation</h3><p style='text-align: center;'>Aucun historique de réservation disponible.</p>";
}

// Retourner le contenu au format JSON
echo json_encode(["resultat" => $reservationContent]);

?>