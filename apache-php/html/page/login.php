<?php
session_start();

$registered = isset($_SESSION['register']) && $_SESSION['register'] === true; // Vérifie si la session 'register' est définie et vraie
$mail_error = isset($_SESSION['mail_error']) && $_SESSION['mail_error'] === true; // Vérifie si la session 'mail_error' est définie et vraie
$psw_error = isset($_SESSION['psw_error']) && $_SESSION['psw_error'] === true; // Vérifie si la session 'psw_error' est définie et vraie




?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="page/css/login.css"> <!-- Ton CSS personnalisé -->
</head>
<body>
    
        <div class="container mt-5">
            <h2 class="text-center">Connexion</h2>
            <?php
            if ($registered) {
               
                echo '<div class="alert alert-success" role="alert">
                        Inscription réussie ! Vous pouvez maintenant vous connecter.
                      </div>';
                // Mettre la session 'register' à false
                $_SESSION['register'] = false; // Change la valeur de la session
               
            }
            if ($mail_error) {
                echo '<div class="alert alert-danger" role="alert">
                        Erreur :  Mail incorrect .
                      </div>';
                // Mettre la session 'register' à false
                $_SESSION['mail_error'] = false; // Change la valeur de la session
            }elseif($psw_error){
                echo '<div class="alert alert-danger" role="alert">
                Erreur :  Mot de passe incorrect .
              </div>';
        // Mettre la session 'register' à false
        $_SESSION['psw_error'] = false; // Change la valeur de la session
            }
            
            
            ?>
            
            <form action="requete/log" method="POST" class="mt-4">
                <div class="form-group">
                    <label for="username">Adresse mail :</label>
                    <input type="email" class="form-control" id="username" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <input type="hidden" name="methode" value="login">
                <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </form>
            <p class="text-center mt-3">Pas encore inscrit ? <a href="/register">S'inscrire</a></p>
        </div>
    
        <!-- Optionnel : inclure jQuery et Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    
</body>
</html>
