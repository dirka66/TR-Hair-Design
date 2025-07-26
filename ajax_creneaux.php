<?php
// Définition d'une constante pour sécuriser les inclusions
define('INDEX_PRINCIPAL', true);

// Démarrage sécurisé de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusion des fichiers de configuration
try {
    require_once 'configs/chemins.class.php';
    require_once Chemins::CONFIGS . 'variables_globales.class.php';
    require_once Chemins::CONFIGS . 'mysql_config.class.php';
    require_once Chemins::MODELES . 'gestion_rendezvous.class.php';
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erreur de configuration : ' . $e->getMessage()]);
    exit();
}

// Vérifier que c'est une requête AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Accès non autorisé']);
    exit();
}

// Récupérer la date depuis la requête GET
$date = $_GET['date'] ?? date('Y-m-d');

// Valider le format de la date
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Format de date invalide']);
    exit();
}

try {
    // Créer une instance de gestion des rendez-vous
    $gestionRendezVous = new gestion_rendezvous();
    
    // Obtenir les créneaux disponibles pour la date
    $creneaux = $gestionRendezVous->obtenirCreneauxDisponibles($date);
    
    // Retourner les créneaux en JSON
    header('Content-Type: application/json');
    echo json_encode($creneaux);
    
} catch (Exception $e) {
    // Log de l'erreur
    error_log("Erreur AJAX créneaux: " . $e->getMessage());
    
    // Retourner une erreur
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erreur lors du chargement des créneaux']);
}
?> 