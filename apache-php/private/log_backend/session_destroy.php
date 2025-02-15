<?php 
// Démarre la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Envoie une réponse JSON indiquant que la session a été désactivée
echo json_encode(["session" => "desactiver"]);

exit();
?>
