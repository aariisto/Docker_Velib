<?php
ob_start();
$headers = getallheaders();
// Vérifie que la méthode et les paramètres sont corrects
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['methode']) || isset($headers['methode']))) {

    $allowed_methods = ["login", "register","session_destroy"]; // Liste des méthodes autorisées

    if(isset($_POST['methode'])){

        $methode = $_POST['methode'];

    }else{
        $methode = $headers['methode'];
    }
    // Vérifie si la méthode fournie est dans la liste des méthodes autorisées
    if (in_array($methode, $allowed_methods)) {
        require_once '/var/www/private/log_backend/' . $methode . '.php';
    } else {
        echo json_encode(["error" => "Invalid method"]);
    }
} else {
    // Réponse d'erreur si l'action est invalide
    echo json_encode(["error" => "Invalid request"]);
}
?>
