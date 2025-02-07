<?php
// Activation du rapport d'erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclusion de l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Configuration de la session AVANT toute sortie
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mettre à 1 si HTTPS
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', 1440);

// Configuration des cookies de session
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false, // Mettre à true si HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Démarrage de la session
session_start();

// Régénération périodique de l'ID de session
if (!isset($_SESSION['last_regeneration']) ||
    time() - $_SESSION['last_regeneration'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Inclusion du fichier de routes
require_once __DIR__ . '/../config/routes.php';