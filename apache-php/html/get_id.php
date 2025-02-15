<?php
header('Content-Type: application/json'); // Indique que la réponse est en JSON
header('Access-Control-Allow-Methods: GET'); // Accepte les requêtes GET
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['token'])) {
        echo json_encode(['id_user' => $_SESSION['id'],'nom'=>$_SESSION['nom'],'mail'=>$_SESSION['email'],'create'=>$_SESSION['token']]);
} else {
    echo json_encode(['error' => 'Unauthorized']);
}
?>
