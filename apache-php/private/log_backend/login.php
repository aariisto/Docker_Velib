
<?php


require '/var/www/private/Model/Model.php';
require '/var/www/private/functions/tokenGEN.php'; 

// Utiliser le namespace Firebase\JWT pour la classe JWT

session_start();

// Connexion à la base de données
$conn = Model::getModel();

// Récupérer les données du formulaire


$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

// Chercher l'utilisateur dans la base de données

$result = $conn->getMail($email);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Vérifier si le mot de passe est correct
    if (password_verify($password, $user['password'])) {
        // Démarrer une session et stocker l'utilisateur dans la session
        
        $_SESSION['email'] = $user['email'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['nom'] = $user['username'];
        $_SESSION['createTime'] = $user['created_at'];// Stocker le nom d'utilisateur dans la session
        $token = generateJWT($_SESSION['id']); //creation du token
        $_SESSION['token'] = $token;//stocket ke token

        // Redirection vers la page protégée après connexion
        header('Location: /home');
        exit(); // Assurez-vous de quitter le script après la redirection
    } else {
        $_SESSION['psw_error'] = true;
        header('Location: /login');
        exit(); // Assurez-vous de quitter le script après la redirection
    }
} else {
    $_SESSION['mail_error'] = true;
    header('Location: /login');
    exit(); // Assurez-vous de quitter le script après la redirection
   
}

$conn->close();
?>
