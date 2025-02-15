<?php
ob_start();
// Récupère l'URI de la requête
$request = $_SERVER['REQUEST_URI']; // bvfhddfihl

// Routeur simple basé sur l'URI
switch ($request) {
    case '/':
        // Redirige vers la page home.php
        include 'page/home.php';
        break;
    case '/home':
        // Redirige vers la page home.php
        include 'page/home.php';
        break;
    case '/login':
        // Redirige vers la page login.php
        include 'page/login.php';
        break;
    case '/register':
        // Redirige vers la page register.php
        include 'page/register.php';
        break;
    case '/account':
        // Redirige vers la page account_page.php
        include 'page/account_page.php';
        break;
    case '/requete/post_get':
            // Appelle le contrôleur LoginController
            include 'controller/PostGetController.php';
            break;
    case '/requete/log':
            // Appelle le contrôleur LoginController
            include 'controller/LogController.php';
            break;
    default:
        // Si la route n'existe pas, afficher une page 404
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        break;
}
?>
