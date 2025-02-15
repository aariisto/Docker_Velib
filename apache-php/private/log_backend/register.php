<?php

require '/var/www/private/Model/Model.php';

session_start();
// Définir le niveau d'erreurs à afficher (E_ALL montre toutes les erreurs)

// Connexion à la base de données
$conn = Model::getModel();

if ($conn->connect_error) {
    die("Connection failed");
}

// Récupérer les données du formulaire

$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

$password = password_hash($password, PASSWORD_BCRYPT); // Hash du mot de passe

// Vérifier si l'utilisateur existe déjà

$result = $conn->getMail($email);

if ($result->num_rows > 0) {
    $_SESSION['register_error'] = true;
    header('Location: /register');
    
} else {
    $_SESSION['register_error'] = false;
    
    // Insérer l'utilisateur dans la base de données
    $result = $conn->addUser($username,$email,$password);
    if ($result) {
        
        $_SESSION['email'] = $email;
        $_SESSION['register'] = true;
        header('Location: /login');
        exit(); // Assurez-vous de quitter le script après la redirection
    } else {
        echo "Erreur";
    }
}

$conn->close();
?>
