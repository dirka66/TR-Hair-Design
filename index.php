<?php 
// Définition d'une constante pour sécuriser les inclusions
define('INDEX_PRINCIPAL', true);

// Démarrage sécurisé de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bufferisation pour éviter les erreurs d'en-têtes
ob_start();

// Inclusion des fichiers de configuration
try {
    require_once 'configs/chemins.class.php';
    require_once Chemins::CONFIGS . 'variables_globales.class.php';
    require_once Chemins::CONFIGS . 'mysql_config.class.php';
} catch (Exception $e) {
    die('Erreur de configuration : ' . $e->getMessage());
}

// Inclusion des modèles de gestion
$modeles = [
    'gestion_basededonnee',
    'gestion_produits',
    'gestion_famille',
    'gestion_horaires',
    'gestion_informations',
    'gestion_admin',
    'gestion_rendezvous',
    'gestion_contact',
    'gestion_galerie'
];

foreach ($modeles as $modele) {
    $fichier = Chemins::MODELES . $modele . '.class.php';
    if (file_exists($fichier)) {
        require_once $fichier;
    }
}

// Inclusion de l'en-tête
$controleursAdmin = ['admin', 'contact', 'horaires', 'produits', 'produitsmoderne', 'familles', 'informations', 'rendezvous', 'galerie'];
$controleurActuel = isset($_GET['controleur']) ? strtolower($_GET['controleur']) : '';
if (!in_array($controleurActuel, $controleursAdmin)) {
    require_once Chemins::VUES_PERMANENTES . 'v_entete.inc.php';
}

// Inclusion des contrôleurs principaux
$controleurs = [
    'ControleurProduits',
    'ControleurHoraires', 
    'ControleurInformations',
    'ControleurFamilles',
    'ControleurAdmin',
    'ControleurRendezVous',
    'ControleurContact',
    'ControleurGalerie'
];

foreach ($controleurs as $controleur) {
    $fichier = Chemins::CONTROLEURS . $controleur . '.class.php';
    if (file_exists($fichier)) {
        require_once $fichier;
    }
}

// Récupération et validation des paramètres
$controleur = isset($_GET['controleur']) ? ucfirst(htmlspecialchars(trim($_GET['controleur']))) : null;
$action = isset($_GET['action']) ? htmlspecialchars(trim($_GET['action'])) : null;

// Routage principal
if (!$controleur) {
    // Page d'accueil par défaut - charger les données pour les cartes
    try {
        $lesServices = GestionProduits::getLesProduits();
    } catch (Exception $e) {
        $lesServices = [];
        error_log("Erreur chargement services: " . $e->getMessage());
    }
    
    // Charger les horaires pour la carte horaires
    try {
        require_once Chemins::MODELES . 'gestion_horaires.class.php';
        $gestionHoraires = new GestionHoraires();
        $lesHoraires = $gestionHoraires->getLesHoraires();
    } catch (Exception $e) {
        $lesHoraires = [];
        error_log("Erreur chargement horaires: " . $e->getMessage());
    }
    
    // Charger les informations pour la carte informations
    try {
        require_once Chemins::MODELES . 'gestion_informations.class.php';
        $gestionInfos = new GestionInformations();
        $lesInformations = $gestionInfos->getLesInformations();
    } catch (Exception $e) {
        $lesInformations = [];
        error_log("Erreur chargement informations: " . $e->getMessage());
    }
    
    // Charger les images de la galerie pour la section galerie
    try {
        require_once Chemins::MODELES . 'gestion_galerie.class.php';
        $lesImagesGalerie = GestionGalerie::getImagesActives();
    } catch (Exception $e) {
        $lesImagesGalerie = [];
        error_log("Erreur chargement galerie: " . $e->getMessage());
    }
    
    require_once Chemins::VUES . "v_sections.inc.php";
} else {
    // Gestion spéciale pour les contrôleurs modernes
    $controleurModerne = null;
    $actionModerne = $action;
    
    switch (strtolower($controleur)) {
        case 'admin':
            if ($action === 'modifierLesHoraires') {
                $controleurModerne = 'ControleurHorairesModerne';
                $actionModerne = 'listerHoraires';
            } else {
                $controleurModerne = 'ControleurAdmin';
            }
            break;
        case 'produits':
            $controleurModerne = 'ControleurProduitsModerne';
            if ($action === 'listerProduits') {
                $actionModerne = 'listerProduits';
            }
            break;
        case 'familles':
            $controleurModerne = 'ControleurFamillesModerne';
            if ($action === 'listerFamilles') {
                $actionModerne = 'listerFamilles';
            } else {
                $actionModerne = $action;
            }
            break;
        case 'informations':
            $controleurModerne = 'ControleurInformations';
            if ($action === 'listerInformations') {
                $actionModerne = 'listerInformations';
            }
            break;
        default:
            // Contrôleurs classiques
            $controleurModerne = "Controleur" . $controleur;
            break;
    }
    
    // Tentative de chargement du contrôleur moderne
    if ($controleurModerne) {
        $fichierControleur = $controleurModerne . ".class.php";
        $cheminFichier = Chemins::CONTROLEURS . $fichierControleur;

        if (file_exists($cheminFichier)) {
            require_once($cheminFichier);
            
            if (class_exists($controleurModerne)) {
                try {
                    $objetControleur = new $controleurModerne();

                    if ($actionModerne && method_exists($objetControleur, $actionModerne)) {
                        // Juste avant l'appel à $objetControleur->$actionModerne();
                        if (
                            strtolower($controleur) === 'horaires' &&
                            ($action === 'afficher' || $actionModerne === 'afficher')
                        ) {
                            if (isset($_SESSION['login_admin'])) {
                                if (method_exists($objetControleur, 'listerHorairesAdmin')) {
                                    $objetControleur->listerHorairesAdmin();
                                    return;
                                }
                            }
                        }
                        $objetControleur->$actionModerne();
                    } else {
                        // Action par défaut
                        if (method_exists($objetControleur, 'index')) {
                            $objetControleur->index();
                        } elseif (method_exists($objetControleur, 'lister' . str_replace('Controleur', '', $controleurModerne))) {
                            $methodeLister = 'lister' . str_replace('ControleurModerne', '', str_replace('Controleur', '', $controleurModerne));
                            if (method_exists($objetControleur, $methodeLister)) {
                                $objetControleur->$methodeLister();
                            } else {
                                require Chemins::VUES . "v_erreur404.inc.php";
                            }
                        } else {
                            require Chemins::VUES . "v_erreur404.inc.php";
                        }
                    }
                } catch (Exception $e) {
                    error_log("Erreur contrôleur moderne : " . $e->getMessage());
                    require Chemins::VUES . "v_erreur404.inc.php";
                }
            } else {
                require Chemins::VUES . "v_erreur404.inc.php";
            }
        } else {
            // Fallback vers les contrôleurs classiques
            $classeControleur = "Controleur" . $controleur;
            $fichierControleur = $classeControleur . ".class.php";
            $cheminFichier = Chemins::CONTROLEURS . $fichierControleur;

            if (file_exists($cheminFichier)) {
                require_once($cheminFichier);
                
                if (class_exists($classeControleur)) {
                    try {
                        $objetControleur = new $classeControleur();

                        if ($action && method_exists($objetControleur, $action)) {
                            $objetControleur->$action();
                        } else {
                            // Action par défaut ou erreur
                            if (method_exists($objetControleur, 'index')) {
                                $objetControleur->index();
                            } else {
                                require Chemins::VUES . "v_erreur404.inc.php";
                            }
                        }
                    } catch (Exception $e) {
                        error_log("Erreur contrôleur classique : " . $e->getMessage());
                        require Chemins::VUES . "v_erreur404.inc.php";
                    }
                } else {
                    require Chemins::VUES . "v_erreur404.inc.php";
                }
            } else {
                require Chemins::VUES . "v_erreur404.inc.php";
            }
        }
    }
}

// Suppression de la gestion spéciale - tous les contrôleurs admin utilisent le système normal

// Inclusion du pied de page
if (!in_array($controleurActuel, $controleursAdmin)) {
    require_once Chemins::VUES_PERMANENTES . 'v_pied.inc.php';
}

// Fin de la bufferisation
ob_end_flush();
?>
