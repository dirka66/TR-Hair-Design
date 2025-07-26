<?php
/**
 * Configuration de base de données pour la production
 * TR Hair Design - Byethost
 */

// Configuration de la base de données Byethost
define('DB_HOST', 'sql112.byethost15.com');
define('DB_NAME', 'b15_39567149_bdd_trhairdesign');
define('DB_USER', 'b15_39567149');
define('DB_PASS', 'Cetintas05**');
define('DB_CHARSET', 'utf8mb4');

// Configuration du site
define('SITE_URL', 'http://trhairdesign.byethost15.com');
define('SITE_NAME', 'TR Hair Design');
define('ENVIRONMENT', 'production');

// Configuration de sécurité
define('DEBUG_MODE', false);
define('DISPLAY_ERRORS', false);
define('ERROR_REPORTING', 0);

// Configuration des sessions
define('SESSION_TIMEOUT', 3600); // 1 heure
define('SECURE_SESSION', true);

// Configuration des emails
define('CONTACT_EMAIL', 'kadircetintas023@gmail.com');
define('ADMIN_EMAIL', 'kadircetintas023@gmail.com');

// Configuration des uploads
define('UPLOAD_MAX_SIZE', 5242880); // 5MB
define('UPLOAD_PATH', 'public/uploads/');

// Configuration des logs
define('LOG_ENABLED', true);
define('LOG_PATH', 'logs/');

// Configuration du cache
define('CACHE_ENABLED', true);
define('BROWSER_CACHE', true);

// Configuration des identifiants admin
define('ADMIN_LOGIN', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // À changer en production

// Configuration des horaires par défaut
define('DEFAULT_OPENING_HOURS', [
    'lundi' => ['fermé'],
    'mardi' => ['09:00', '19:00'],
    'mercredi' => ['09:00', '19:00'],
    'jeudi' => ['09:00', '19:00'],
    'vendredi' => ['09:00', '19:00'],
    'samedi' => ['09:00', '19:00'],
    'dimanche' => ['fermé']
]);

// Configuration des services par défaut
define('DEFAULT_SERVICES', [
    'coupe_homme' => ['nom' => 'Coupe Homme', 'prix' => 25, 'duree' => 30],
    'coupe_femme' => ['nom' => 'Coupe Femme', 'prix' => 35, 'duree' => 45],
    'coloration' => ['nom' => 'Coloration', 'prix' => 65, 'duree' => 90],
    'balayage' => ['nom' => 'Balayage', 'prix' => 85, 'duree' => 120]
]);

// Configuration des catégories par défaut
define('DEFAULT_CATEGORIES', [
    'coupes' => ['nom' => 'Coupes', 'couleur' => '#3498db', 'icone' => 'fas fa-cut'],
    'colorations' => ['nom' => 'Colorations', 'couleur' => '#e74c3c', 'icone' => 'fas fa-palette'],
    'soins' => ['nom' => 'Soins', 'couleur' => '#2ecc71', 'icone' => 'fas fa-spa'],
    'coiffures' => ['nom' => 'Coiffures', 'couleur' => '#f39c12', 'icone' => 'fas fa-magic']
]);

// Configuration des informations de contact
define('SALON_ADDRESS', '123 Rue de la Coiffure, 75001 Paris');
define('SALON_PHONE', '+33 1 23 45 67 89');
define('SALON_EMAIL', 'contact@trhairdesign.fr');

// Configuration des réseaux sociaux
define('FACEBOOK_URL', 'https://facebook.com/trhairdesign');
define('INSTAGRAM_URL', 'https://instagram.com/trhairdesign');
define('YOUTUBE_URL', 'https://youtube.com/trhairdesign');

// Configuration du fuseau horaire
define('TIMEZONE', 'Europe/Paris');

// Application des configurations
date_default_timezone_set(TIMEZONE);
ini_set('display_errors', DISPLAY_ERRORS ? '1' : '0');
error_reporting(ERROR_REPORTING);

// Création des dossiers nécessaires
$directories = [UPLOAD_PATH, LOG_PATH];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Configuration des sessions sécurisées
if (SECURE_SESSION) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
}

// Fonction de log personnalisée
function logError($message, $type = 'ERROR') {
    if (LOG_ENABLED) {
        $logFile = LOG_PATH . 'error.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$type] $message" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}

// Gestionnaire d'erreurs personnalisé
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (LOG_ENABLED) {
        logError("$errstr in $errfile on line $errline", 'ERROR');
    }
    return true;
});

// Gestionnaire d'exceptions personnalisé
set_exception_handler(function($exception) {
    if (LOG_ENABLED) {
        logError($exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine(), 'EXCEPTION');
    }
    if (DEBUG_MODE) {
        throw $exception;
    } else {
        http_response_code(500);
        echo "Une erreur s'est produite. Veuillez réessayer plus tard.";
    }
});

?> 