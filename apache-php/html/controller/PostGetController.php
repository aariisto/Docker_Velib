<?php

require '/var/www/private/Model/Model.php';
require '/var/www/private/functions/tokenCHECK.php'; 

session_start();

header('Content-Type: application/json');
$headers = getallheaders();

$input = file_get_contents("php://input"); // Récupère les données envoyées
$data = json_decode($input, true); // Décode les données JSON

// Vérifie que la méthode et les paramètres sont corrects
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($headers['methode']) 
        && isset($_SESSION['id']) && isset($_SESSION['token']) && verifyJWT($headers['csrf-token'],$_SESSION['id'])) {

    $allowed_methods = ["post_search", "post_order","get_search","remove_search","get_reservation"]; // Liste des méthodes autorisées

    // Vérifie si la méthode fournie est dans la liste des méthodes autorisées
    if (in_array($headers['methode'], $allowed_methods)) {
        require_once '/var/www/private/api_files/' . $headers['methode'] . '.php';
        
    } else {
        echo json_encode(["error" => "Invalid method"]);
    }
} else {
    // Réponse d'erreur si l'action est invalide
    http_response_code(401); // Erreur 400 : Mauvaise requête
    echo json_encode(["error" => "Votre session a expiré. Veuillez vous reconnecter.","error_code"=> "erreur code: 1","token"=> true]);
    exit();
}
?>
