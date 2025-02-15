<?php

session_start();

$registered_error = isset($_SESSION['register_error']) && $_SESSION['register_error'] === true; // Vérifie si la session 'register' est définie et vraie

?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inscription</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="page/css/register.css">
    </head>
<body>
    
        <div class="container mt-5">
            <h2 class="text-center">Inscription</h2>
            <?php
            if ($registered_error) {
               
                echo '<div class="alert alert-danger" role="alert">
                        Erreur :  Mail deja utiliser .
                      </div>';
                // Mettre la session 'register' à false
                $_SESSION['register_error'] = false; // Change la valeur de la session
            }
            ?>
            <form action="requete/log" method="POST" class="mt-4">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <input type="hidden" name="methode" value="register">
                <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
            </form>
            <p class="text-center mt-3">Déjà un compte ? <a href="login">Se connecter</a></p>
        </div>
    
        <!-- Optionnel : inclure jQuery et Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   
    
</body>
</html>
