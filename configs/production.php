<?php
/**
 * Configuration de production pour TR Hair Design
 * Optimisé pour l'hébergement en ligne
 */

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'trhairdesign');
define('DB_USER', 'votre_utilisateur_db');
define('DB_PASS', 'votre_mot_de_passe_db');
define('DB_CHARSET', 'utf8mb4');

// Configuration de l'application
define('APP_NAME', 'TR Hair Design');
define('APP_VERSION', '2.0.0');
define('APP_ENV', 'production');

// Configuration de sécurité
define('SECURE_SESSION', true);
define('SESSION_LIFETIME', 3600); // 1 heure
define('CSRF_TOKEN_LIFETIME', 1800); // 30 minutes

// Configuration des chemins
define('BASE_URL', 'https://votre-domaine.com');
define('UPLOAD_PATH', 'public/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Configuration des emails
define('SMTP_HOST', 'smtp.votre-hebergeur.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'contact@votre-domaine.com');
define('SMTP_PASS', 'votre_mot_de_passe_smtp');
define('SMTP_SECURE', 'tls');

// Configuration des logs
define('LOG_PATH', 'logs/');
define('LOG_LEVEL', 'ERROR'); // ERROR, WARNING, INFO, DEBUG

// Configuration du cache
define('CACHE_ENABLED', true);
define('CACHE_PATH', 'cache/');
define('CACHE_LIFETIME', 3600); // 1 heure

// Configuration des performances
define('COMPRESSION_ENABLED', true);
define('MINIFY_CSS', true);
define('MINIFY_JS', true);

// Configuration de maintenance
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_IPS', ['127.0.0.1', '::1']);

// Configuration des notifications
define('ADMIN_EMAIL', 'admin@votre-domaine.com');
define('NOTIFICATION_EMAIL', 'notifications@votre-domaine.com');

// Configuration des sauvegardes
define('BACKUP_ENABLED', true);
define('BACKUP_PATH', 'backups/');
define('BACKUP_RETENTION', 7); // jours

// Configuration des statistiques
define('ANALYTICS_ENABLED', true);
define('GOOGLE_ANALYTICS_ID', 'GA-XXXXXXXXX-X');

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
?> 