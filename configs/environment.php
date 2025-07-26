<?php
/**
 * Configuration d'environnement pour TR Hair Design
 * Détection automatique de l'environnement
 */

// Détection de l'environnement
function detectEnvironment() {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $serverName = $_SERVER['SERVER_NAME'] ?? '';
    
    // Environnements de développement
    $devPatterns = [
        'localhost',
        '127.0.0.1',
        '::1',
        'dev.',
        'test.',
        'local.',
        'wamp',
        'xampp',
        'mamp'
    ];
    
    foreach ($devPatterns as $pattern) {
        if (strpos($host, $pattern) !== false || strpos($serverName, $pattern) !== false) {
            return 'development';
        }
    }
    
    return 'production';
}

// Configuration selon l'environnement
$environment = detectEnvironment();

if ($environment === 'development') {
    // Configuration de développement
    define('APP_ENV', 'development');
    define('DEBUG_MODE', true);
    define('ERROR_REPORTING', E_ALL);
    define('DISPLAY_ERRORS', true);
    define('LOG_LEVEL', 'DEBUG');
    
    // Base de données de développement
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'trhairdesign');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_CHARSET', 'utf8mb4');
    
    // URLs de développement
    define('BASE_URL', 'http://localhost/trhairdesign');
    define('ADMIN_URL', 'http://localhost/trhairdesign/index.php?controleur=Admin');
    
    // Configuration des emails (désactivée en dev)
    define('SMTP_ENABLED', false);
    define('ADMIN_EMAIL', 'dev@localhost');
    
    // Cache désactivé en développement
    define('CACHE_ENABLED', false);
    define('MINIFY_CSS', false);
    define('MINIFY_JS', false);
    
} else {
    // Configuration de production
    define('APP_ENV', 'production');
    define('DEBUG_MODE', false);
    define('ERROR_REPORTING', E_ERROR | E_WARNING | E_PARSE);
    define('DISPLAY_ERRORS', false);
    define('LOG_LEVEL', 'ERROR');
    
    // Base de données de production (à configurer)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'trhairdesign');
    define('DB_USER', 'votre_utilisateur_db');
    define('DB_PASS', 'votre_mot_de_passe_db');
    define('DB_CHARSET', 'utf8mb4');
    
    // URLs de production (à configurer)
    define('BASE_URL', 'https://votre-domaine.com');
    define('ADMIN_URL', 'https://votre-domaine.com/index.php?controleur=Admin');
    
    // Configuration des emails de production
    define('SMTP_ENABLED', true);
    define('ADMIN_EMAIL', 'admin@votre-domaine.com');
    
    // Cache activé en production
    define('CACHE_ENABLED', true);
    define('MINIFY_CSS', true);
    define('MINIFY_JS', true);
}

// Configuration commune
define('APP_NAME', 'TR Hair Design');
define('APP_VERSION', '2.0.0');
define('TIMEZONE', 'Europe/Paris');
define('LOCALE', 'fr_FR');
define('CHARSET', 'UTF-8');

// Configuration de sécurité
define('SECURE_SESSION', $environment === 'production');
define('SESSION_LIFETIME', 3600);
define('CSRF_TOKEN_LIFETIME', 1800);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes

// Configuration des chemins
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/application');
define('CONFIGS_PATH', ROOT_PATH . '/configs');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('CACHE_PATH', ROOT_PATH . '/cache');
define('LOGS_PATH', ROOT_PATH . '/logs');

// Configuration des fichiers
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx']);

// Configuration des performances
define('COMPRESSION_ENABLED', true);
define('BROWSER_CACHE_ENABLED', true);
define('BROWSER_CACHE_DURATION', 2592000); // 30 jours

// Configuration des notifications
define('NOTIFICATION_EMAIL', 'notifications@votre-domaine.com');
define('CONTACT_EMAIL', 'contact@votre-domaine.com');

// Configuration des réseaux sociaux
define('FACEBOOK_URL', 'https://facebook.com/votre-page');
define('INSTAGRAM_URL', 'https://instagram.com/votre-compte');
define('YOUTUBE_URL', 'https://youtube.com/votre-chaine');

// Configuration des horaires par défaut
define('DEFAULT_OPENING_HOURS', [
    'lundi' => ['09:00', '19:00'],
    'mardi' => ['09:00', '19:00'],
    'mercredi' => ['09:00', '19:00'],
    'jeudi' => ['09:00', '19:00'],
    'vendredi' => ['09:00', '19:00'],
    'samedi' => ['09:00', '18:00'],
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

// Application des configurations
date_default_timezone_set(TIMEZONE);
ini_set('display_errors', DISPLAY_ERRORS ? '1' : '0');
error_reporting(ERROR_REPORTING);

// Création des dossiers nécessaires
$directories = [UPLOAD_PATH, CACHE_PATH, LOGS_PATH];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Configuration des logs
if (!defined('LOG_FILE')) {
    define('LOG_FILE', LOGS_PATH . '/app_' . date('Y-m-d') . '.log');
}

// Fonction de log
function logMessage($level, $message, $context = []) {
    if (LOG_LEVEL === 'DEBUG' || $level === 'ERROR') {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [$level] $message";
        if (!empty($context)) {
            $logEntry .= ' ' . json_encode($context);
        }
        $logEntry .= PHP_EOL;
        
        file_put_contents(LOG_FILE, $logEntry, FILE_APPEND | LOCK_EX);
    }
}

// Fonction de debug (uniquement en développement)
function debug($data) {
    if (DEBUG_MODE) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }
}

// Fonction de nettoyage des logs
function cleanOldLogs($days = 7) {
    $logFiles = glob(LOGS_PATH . '/app_*.log');
    $cutoff = time() - ($days * 24 * 60 * 60);
    
    foreach ($logFiles as $file) {
        if (filemtime($file) < $cutoff) {
            unlink($file);
        }
    }
}

// Nettoyage automatique des logs (une fois par jour)
if (!file_exists(LOGS_PATH . '/last_cleanup.txt') || 
    (time() - filemtime(LOGS_PATH . '/last_cleanup.txt')) > 86400) {
    cleanOldLogs();
    file_put_contents(LOGS_PATH . '/last_cleanup.txt', date('Y-m-d H:i:s'));
}
?> 