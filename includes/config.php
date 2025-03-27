<?php
// Empêcher l'accès direct au fichier
if (!defined('SECURE_ACCESS') && basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Accès direct non autorisé');
}

// Désactiver l'affichage des erreurs en production
error_reporting(0);
ini_set('display_errors', 0);

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'notes_esigelec');

// Configuration de la sécurité
define('HASH_COST', 12); // Coût pour bcrypt
define('SESSION_LIFETIME', 3600); // Durée de vie de la session en secondes

// Configuration des sessions
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mettre à 1 uniquement si HTTPS est utilisé
ini_set('session.cookie_samesite', 'Lax'); // Plus permissif que 'Strict' pour le développement local

// Établir la connexion à la base de données avec PDO
try {
    $dbh = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        )
    );
} catch (PDOException $e) {
    // Logger l'erreur au lieu de l'afficher
    error_log("Erreur de connexion à la base de données: " . $e->getMessage());
    die("Une erreur est survenue lors de la connexion à la base de données.");
}

// Connexion mysqli (si nécessaire)
try {
    $dbh1 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($dbh1->connect_error) {
        throw new Exception("Erreur de connexion: " . $dbh1->connect_error);
    }
    $dbh1->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log("Erreur de connexion mysqli: " . $e->getMessage());
    die("Une erreur est survenue lors de la connexion à la base de données.");
}

// Fonction de nettoyage des entrées
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Fonction de génération de token CSRF
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Fonction de vérification de token CSRF
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}
?>