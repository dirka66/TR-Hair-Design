<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarrer la session uniquement si elle n'est pas déjà active
}
ob_start(); // Pour éviter erreur COOKIES

require_once 'configs/chemins.class.php';
require_once Chemins::CONFIGS . 'variables_globales.class.php';
require_once Chemins::CONFIGS . 'mysql_config.class.php';
require_once Chemins::VUES_PERMANENTES . 'v_entete.inc.php';

// Inclusion des contrôleurs principaux
require_once Chemins::CONTROLEURS . 'ControleurProduits.class.php';
require_once Chemins::CONTROLEURS . 'ControleurHoraires.class.php';
require_once Chemins::CONTROLEURS . 'ControleurInformations.class.php';
require_once Chemins::CONTROLEURS . 'ControleurFamilles.class.php';

// Déterminer le contrôleur demandé (avec validation)
$controleur = isset($_GET['controleur']) ? ucfirst(htmlspecialchars($_GET['controleur'])) : null;
$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : null;

if (!$controleur) {
    require_once Chemins::VUES . "v_sections.inc.php";
} else {
    $classeControleur = "Controleur" . $controleur;
    $fichierControleur = $classeControleur . ".class.php";
    $cheminFichier = Chemins::CONTROLEURS . $fichierControleur;

    if (file_exists($cheminFichier)) {
        require_once($cheminFichier);
        
        if (class_exists($classeControleur)) {
            $objetControleur = new $classeControleur();

            if ($action && method_exists($objetControleur, $action)) {
                $objetControleur->$action();
            } else {
                require Chemins::VUES . "v_erreur404.inc.php";
            }
        } else {
            require Chemins::VUES . "v_erreur404.inc.php";
        }
    } else {
        require Chemins::VUES . "v_erreur404.inc.php";
    }
}

// 🔥 INCLUSION CONDITIONNELLE 🔥
if (isset($_GET['controleur'], $_GET['action']) && 
    $_GET['controleur'] === 'Famille' && $_GET['action'] === 'gererFamilles') {
    require_once Chemins::VUES . 'partie_admin/v_gestionfamille.inc.php';
}

require_once Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
?>
