<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once Chemins::MODELES . 'gestion_basededonnee.class.php';

class ControleurHoraires {

    public function __construct() {
        require_once Chemins::MODELES . 'gestion_horaires.class.php';
    }

    // Méthode pour afficher les horaires
    public function afficher() {
        require_once Chemins::MODELES . 'gestion_horaires.class.php';
        $lesHoraires = GestionHoraires::getLesHoraires();
        VariablesGlobales::$lesHoraires = $lesHoraires;
        require_once Chemins::VUES . 'v_horaires.inc.php';
    }

    // Méthode pour afficher les horaires dans l'admin
    public function listerHorairesAdmin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }
        
        $lesHoraires = GestionHoraires::getLesHoraires();
        $titrePage = "Gestion des Horaires";
        $messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
        $messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
        unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
        
        if (!defined('INDEX_PRINCIPAL')) {
            define('INDEX_PRINCIPAL', true);
        }
        
        // Utiliser le layout admin commun
        $pageActive = 'horaires';
        
        // Capturer le contenu de la vue
        ob_start();
        include_once("application/vues/partie_admin/v_gestion_horaires.inc.php");
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }

    // Méthode pour afficher le formulaire de modification
    public function afficherModifierHoraires() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }
        
        $lesHoraires = GestionHoraires::getLesHoraires();
        VariablesGlobales::$lesHoraires = $lesHoraires;
        
        $titrePage = "Modifier les Horaires";
        $messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
        $messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
        unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
        
        if (!defined('INDEX_PRINCIPAL')) {
            define('INDEX_PRINCIPAL', true);
        }
        
        // Utiliser le layout admin commun
        $pageActive = 'horaires';
        
        // Capturer le contenu de la vue
        ob_start();
        include_once("application/vues/partie_admin/v_modifier_horaire.inc.php");
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }

    // Méthode pour sauvegarder les modifications des horaires
    public function modifierLesHoraires() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $pdo = GestionBaseDeDonnees::getConnexion();
                
                // Récupérer les données du formulaire
                if (isset($_POST['idHoraire']) && is_array($_POST['idHoraire'])) {
                    foreach ($_POST['idHoraire'] as $index => $idHoraire) {
                        // Récupérer les valeurs (inversé : coché = ouvert = 0, décoché = fermé = 1)
                        $ferme = isset($_POST['ouvert'][$index]) ? 0 : 1;
                        $ouvertureMatin = !empty($_POST['ouvertureMatin'][$index]) ? $_POST['ouvertureMatin'][$index] : null;
                        $fermetureMatin = !empty($_POST['fermetureMatin'][$index]) ? $_POST['fermetureMatin'][$index] : null;
                        $ouvertureAprem = !empty($_POST['ouvertureAprem'][$index]) ? $_POST['ouvertureAprem'][$index] : null;
                        $fermetureAprem = !empty($_POST['fermetureAprem'][$index]) ? $_POST['fermetureAprem'][$index] : null;

                        // Préparer et exécuter la requête de mise à jour
                        $stmt = $pdo->prepare("UPDATE horaire SET 
                            ferme = :ferme,
                            heureOuvertureMatin = :ouvertureMatin,
                            heureFermetureMatin = :fermetureMatin,
                            heureOuvertureAprem = :ouvertureAprem,
                            heureFermetureAprem = :fermetureAprem
                            WHERE idHoraire = :idHoraire");
                        
                        $result = $stmt->execute([
                            ':ferme' => $ferme,
                            ':ouvertureMatin' => $ouvertureMatin,
                            ':fermetureMatin' => $fermetureMatin,
                            ':ouvertureAprem' => $ouvertureAprem,
                            ':fermetureAprem' => $fermetureAprem,
                            ':idHoraire' => $idHoraire
                        ]);
                        
                        if (!$result) {
                            throw new Exception("Erreur lors de la mise à jour de l'horaire ID: $idHoraire");
                        }
                    }
                    
                    $_SESSION['message_succes'] = "Les horaires ont été mis à jour avec succès.";
                } else {
                    throw new Exception("Aucune donnée d'horaire reçue.");
                }
                
            } catch (Exception $e) {
                $_SESSION['message_erreur'] = "Erreur lors de la mise à jour des horaires : " . $e->getMessage();
                error_log("Erreur modification horaires: " . $e->getMessage());
            }
        }
        
        // Redirection vers la page de gestion des horaires
        header("Location: index.php?controleur=Horaires&action=listerHorairesAdmin");
        exit();
    }

    // Méthode pour sauvegarder tous les horaires (ancienne méthode, gardée pour compatibilité)
    public function sauvegarderTous() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }
        
        if (!empty($_POST['horaires'])) {
            try {
                $horaires = $_POST['horaires'];
                foreach ($horaires as $jour => $data) {
                    $idHoraire = isset($data['idHoraire']) ? (int)$data['idHoraire'] : null;
                    $ferme = (isset($data['ouvert']) && $data['ouvert'] == '1') ? 0 : 1;
                    $heureOuvertureMatin = $data['ouverture_matin'] ?? null;
                    $heureFermetureMatin = $data['fermeture_matin'] ?? null;
                    $heureOuvertureAprem = $data['ouverture_apres_midi'] ?? null;
                    $heureFermetureAprem = $data['fermeture_soir'] ?? null;
                    
                    if ($idHoraire) {
                        GestionHoraires::updateHoraire(
                            $idHoraire,
                            $heureOuvertureMatin,
                            $heureFermetureMatin,
                            $heureOuvertureAprem,
                            $heureFermetureAprem,
                            $ferme
                        );
                    }
                }
                $_SESSION['message_succes'] = "Les horaires ont été mis à jour avec succès.";
            } catch (Exception $e) {
                $_SESSION['message_erreur'] = "Erreur lors de la mise à jour des horaires : " . $e->getMessage();
                error_log("Erreur sauvegarde horaires: " . $e->getMessage());
            }
        }
        
        header("Location: index.php?controleur=Horaires&action=listerHorairesAdmin");
        exit();
    }
}

?>
