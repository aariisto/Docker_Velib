<?php
class Model {
    private $bd; // Attribut privé contenant l'objet mysqli
    private static $instance = null; // Attribut qui contiendra l'unique instance du modèle

    /**
     * Constructeur créant l'objet mysqli et l'affectant à $bd
     */
    private function __construct() {
        // Activer le mode exceptions pour mysqli
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            // Connexion à la base de données
            $this->bd = new mysqli('mysql', 'test', 'yannel', 'user');
            $this->bd->set_charset('utf8'); // Définir l'encodage
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_0",
                "token" => false
            ]));
        }
    }

    /**
     * Méthode permettant de récupérer l'instance de la classe Model
     */
    public static function getModel() {
        // Si la classe n'a pas encore été instanciée
        if (self::$instance === null) {
            self::$instance = new self(); // On l'instancie
        }
        return self::$instance; // On retourne l'instance
    }

    /**
     * Insère une recherche dans la base de données.
     *
     * Cette fonction insère une recherche dans la table `recherches` en fonction de l'ID du client, 
     * de la recherche, du résultat et de l'ID de la station. Si l'ID de la station est égal à 0, 
     * l'ID de la station n'est pas inclus dans l'insertion.
     *
     * @param int $id L'ID du client.
     * @param string $search La recherche effectuée par le client.
     * @param int $station_id L'ID de la station (0 si non applicable).
     * @param int $resultat Le résultat de la recherche.
     * @return array Un tableau contenant le succès de l'opération et un message.
     * @throws Exception Si une erreur survient lors de la préparation ou de l'exécution de la requête SQL.
     */
    public function postSearch($id, $search, $station_id, $resultat) {
        // Vérifier si l'ID de la station est égal à 0 pour déterminer la requête SQL appropriée
        if ($station_id === 0) {
            // Donc il a trouver une addresse valide
            $sql = "INSERT INTO recherches (client_id, recherche, resultat) VALUES (?, ?, ?)";
        } else {
            // Sinon il a trouver une station
            $sql= "INSERT INTO recherches (client_id, resultat, station_id) VALUES (?, ?, ?)";
        }

        try {
            // Préparer la requête SQL
            $stmt = $this->bd->prepare($sql);
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_1",
                "token" => false
            ]));
            exit();
        }

        // Lier les paramètres à la requête préparée en fonction de l'ID de la station
        if ($station_id === 0) {
            // si c'etait une addresse pas la peine de mettre l'id de la station
            $stmt->bind_param("isi", $id, $search, $resultat); // "i" pour entier, "s" pour chaîne
        } else {
            // si c'etait une station on met l'id de la station
            $stmt->bind_param("iii", $id, $resultat, $station_id); // "i" pour entier
        }

        try {
            // Exécuter la requête
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_2",
                "token" => false
            ]));
            exit();
        }

        // Retourner le succès de l'opération
        return [
            'success' => true,
            'message' => "Recherche insérée avec succès : client_id = $id, recherche = $search"
        ];
    }

    /**
     * Insère une nouvelle réservation dans la base de données.
     *
     * @param int $confirmationID L'identifiant de confirmation de la réservation.
     * @param int $type Le type de vélo réservé.
     * @param int $id L'identifiant du client.
     * @param int $station_id L'identifiant de la station où le vélo est réservé.
     * @return array Un tableau contenant le statut de succès et un message.
     * @throws Exception Si une erreur survient lors de la préparation ou de l'exécution de la requête SQL.
     */
    public function postOrder($confirmationID, $type, $id, $station_id) {
        // Préparation de la requête SQL pour insérer une nouvelle réservation
        $sql = "INSERT INTO reservations (confirmationID, id_velo, client_id, station_id) VALUES (?, ?, ?, ?)";

        try {
            // Préparation de la requête avec la base de données
            $stmt = $this->bd->prepare($sql);
        } catch (mysqli_sql_exception $e) {
            // Gestion des erreurs de préparation de la requête
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_1",
                "token" => false
            ]));
            exit();
        }

        // Liaison des paramètres à la requête préparée
        $stmt->bind_param("iiii", $confirmationID, $type, $id, $station_id);

        try {
            // Exécution de la requête
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Gestion des erreurs d'exécution de la requête
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_2",
                "token" => false
            ]));
            exit();
        }

        // Retourne un tableau indiquant le succès de l'opération
        return [
            'success' => true,
            'message' => "Réservation insérée avec succès : client_id = $id, confirmationID = $confirmationID"
        ];
    }
    
    
    /**
     * Récupère des reservations pour un client spécifique.
     *
     * @param int $id_client L'ID du client.
     * @return array Un tableau associatif contenant les résultats de la recherche.
     *               - `id` (int) : L'identifiant unique de la recherche.
     *               - `client_id` (int) : L'identifiant du client associé à la recherche.
     *               - `id_velo` (int) : L'identifiant du vélo concerné(type).
     *               - `station_id` (int) : L'identifiant de la station de vélo.
     *               - `station` (string) : Le nom de la station associée.
     *               - `confirmationID` (string) : L'identifiant de confirmation de la recherche.
     *               - `create_time` (string) : La date et l'heure de création de la recherche (format YYYY-MM-DD HH:MM:SS).
     *               - `lat` (float) : La latitude de l'emplacement de la recherche.
     *               - `lon` (float) : La longitude de l'emplacement de la recherche.
     *               - `type` (string) : Le type de velo .
     *                 Ou bien Null si ya pas de resultat
     *  @throws Exception Si une erreur survient lors de la préparation ou de l'exécution de la requête SQL.
     */
    public function getOrder($id_client) {
        $responses = []; // Tableau pour stocker les résultats combinés

        // Première requête pour id_velo = 1
        try {
            $sql1 = "SELECT * FROM reservations_vue WHERE client_id = ? AND id_velo = 1;";
            $stmt1 = $this->bd->prepare($sql1);
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_1",
                "token" => false
            ]));
            exit();
        }

        // Liaison des paramètres et exécution de la requête
        $stmt1->bind_param("i", $id_client);
        try {
            $stmt1->execute();
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_2",
                "token" => false
            ]));
            exit();
        }

        // Récupérer les résultats de la première requête
        $result1 = $stmt1->get_result();
        // fetch_all(MYSQLI_ASSOC) : récupère tous les résultats sous forme de tableau associatif
        // array_merge() : fusionne ces résultats avec le tableau existant $responses
        $responses = array_merge($responses, $result1->fetch_all(MYSQLI_ASSOC));
        $stmt1->close();

        // Deuxième requête pour id_velo = 2
        try {
            $sql2 = "SELECT * FROM reservations_vue WHERE client_id = ? AND id_velo = 2;";
            $stmt2 = $this->bd->prepare($sql2);
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_1",
                "token" => false
            ]));
            exit();
        }

        // Liaison des paramètres et exécution de la requête
        $stmt2->bind_param("i", $id_client);
        try {
            $stmt2->execute();
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_2",
                "token" => false
            ]));
            exit();
        }

        // Récupérer les résultats de la deuxième requête
        $result2 = $stmt2->get_result();
        // fetch_all(MYSQLI_ASSOC) : récupère tous les résultats sous forme de tableau associatif
        // array_merge() : fusionne ces résultats avec le tableau existant $responses
        $responses = array_merge($responses, $result2->fetch_all(MYSQLI_ASSOC));
        $stmt2->close();

        // Trier les résultats combinés par `create_time` (plus petit au plus grand)
        usort($responses, function ($a, $b) {
            return strtotime($a['create_time']) <=> strtotime($b['create_time']);
        });

        return $responses;
    }

    /**
     * Récupère les recherches pour un client donné.
     *
     * Cette fonction exécute une requête SQL préparée pour sélectionner toutes les recherches
     * associées à un client spécifique à partir de la vue `recherches_vue`.
     *
     * @param int $id_client L'identifiant du client pour lequel récupérer les recherches.
     * @return array Un tableau associatif contenant les résultats de la recherche.
     *               - `id` (int) : L'identifiant unique de la recherche.
     *               - `client_id` (int) : L'identifiant du client qui a effectué la recherche.
     *               - `station_id` (int) : L'identifiant de la station associée à la recherche.
     *               - `recherche` (string) : Le texte ou la requête de recherche effectuée.
     *               - `created_at` (string) : La date et l'heure de création de la recherche (format YYYY-MM-DD HH:MM:SS).
     *               - `resultat` (string) : Le résultat obtenu suite à la recherche.
     *               - `lat` (float) : La latitude de l'emplacement de la recherche.
     *               - `lon` (float) : La longitude de l'emplacement de la recherche.
     *               - `station` (string) : Le nom de la station associée à la recherche.
     *                Ou bien Null si ya pas de resultat
     * @throws Exception Si une erreur survient lors de la préparation ou de l'exécution de la requête SQL.
     */
    public function getSearch($id_client) {
        try {
            // Requête SQL préparée
            $sql = "SELECT * FROM recherches_vue WHERE client_id = ?";
            $stmt = $this->bd->prepare($sql);
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_1",
                "token" => false
            ]));
            exit();
        }

        // Liaison des paramètres
        $stmt->bind_param("i", $id_client);

        try {
            // Exécuter la requête
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_2",
                "token" => false
            ]));
            exit();
        }

        // Récupérer les résultats
        $result = $stmt->get_result();
        $response = [];
        // Vérifier les lignes trouvées
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        }
        return $response;
    }

    /**
     * Supprime une recherche de la base de données.
     *
     * @param int $id_search L'ID de la recherche à supprimer.
     * @return array Un tableau contenant le succès de l'opération et un message.
     * @throws Exception Si une erreur survient lors de la préparation ou de l'exécution de la requête SQL.
     */
    public function removeSearch($id_search) {
        try {
            // Requête SQL préparée
            $sql = "DELETE FROM recherches WHERE id = ?";
            $stmt = $this->bd->prepare($sql);
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_1",
                "token" => false
            ]));
            exit();
        }

        // Liaison des paramètres
        $stmt->bind_param("i", $id_search);

        try {
            // Exécuter la requête
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_2",
                "token" => false
            ]));
            exit();
        }

        // Retourner le succès de l'opération
        return [
            'success' => true,
            'message' => "Suppression réussie : recherche ID = $id_search"
        ];
    }

    /**
     * Récupère les informations d'un utilisateur en fonction de son adresse e-mail.
     *
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @return mysqli_result|false Un objet `mysqli_result` contenant le résultat de la requête, ou `false` en cas d'échec.
     * @throws Exception Si une erreur survient lors de la préparation ou de l'exécution de la requête SQL.
     */
    public function getMail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";

        try {
            // Préparer la requête SQL
            $stmt = $this->bd->prepare($sql);
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_1",
                "token" => false
            ]));
            exit();
        }

        // Liaison des paramètres (le "s" indique que c'est une chaîne de caractères)
        $stmt->bind_param("s", $email);

        try {
            // Exécuter la requête
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_2",
                "token" => false
            ]));
            exit();
        }

        // Retourner le résultat de la requête
        return $stmt->get_result();
    }

    /**
     * Ajoute un nouvel utilisateur dans la base de données.
     *
     * @param string $username Le nom d'utilisateur.
     * @param string $email L'adresse e-mail de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * @return bool Retourne true si l'utilisateur a été ajouté avec succès, sinon false.
     */
    public function addUser($username, $email, $password) {
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

        try {
            // Préparer la requête
            $stmt = $this->bd->prepare($sql);
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_1",
                "token" => false
            ]));
            exit();
        }

        // Liaison des paramètres
        $stmt->bind_param("sss", $username, $email, $password);

        try {
            // Exécuter la requête
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Gérer l'exception et renvoyer un JSON personnalisé
            throw new Exception(json_encode([
                "error" => "Le serveur n'a pas pu traiter votre demande. Veuillez réessayer ultérieurement.",
                "error_code" => "erreur code: 3_2",
                "token" => false
            ]));
            exit();
        }
    }



    // Méthode pour fermer la connexion à la base de données
    public function close() {
        $this->bd->close();
    }
}
?>
