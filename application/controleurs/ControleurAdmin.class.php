<?php

class ControleurAdmin {

    public function __construct() {
        require_once Chemins::MODELES . 'gestion_admin.class.php';
        require_once Chemins::MODELES . 'gestion_horaires.class.php';
    }

    public function afficherIndex() {
    if (isset($_SESSION['login_admin'])) {
        // Charge la vue d'index de l'admin si l'administrateur est connecté
        require Chemins::VUES_ADMIN . 'v_index_admin.inc.php';
    } else {
        // Charge la vue de connexion si l'administrateur n'est pas connecté
        require Chemins::VUES_ADMIN . 'v_connexion.inc.php';
    }
}


    public function verifierConnexion() {
        if (isset($_POST['login'], $_POST['passe'])) {
            if (GestionAdmin::isAdminOK($_POST['login'], $_POST['passe'])) {
                $_SESSION['login_admin'] = $_POST['login'];

                if (isset($_POST['connexion_auto'])) {
                    setcookie('login_admin', $_POST['login'], time() + 7 * 24 * 3600, '/', false, false, true);
                }
// Le cookie sera valable dans ce cas 1 semaine (7 jours)

                require Chemins::VUES_ADMIN . 'v_index_admin.inc.php';
            } else {
                require Chemins::VUES_ADMIN . 'v_acces_interdit.inc.php';
            }
        } else {
            echo "Erreur : les informations de connexion sont manquantes.";
        }
    }

    public function seDeconnecter() {
// Suppression des variables de session et de la session
        $_SESSION = array();
        session_destroy();
        setcookie('login_admin', ''); //suppression du cookie en vidant simplement la chaîne
        header("Location:index.php");
    }

    public function modifierLesHoraires() {
        if (isset($_SESSION['login_admin'])) {
            $lesHoraires = GestionHoraires::getLesHoraires();
            if ($lesHoraires !== null) {
                VariablesGlobales::$lesHoraires = $lesHoraires;

// Inclure la vue qui utilise la variable $lesHoraires
                require Chemins::VUES_ADMIN . 'v_modifier_horaire.inc.php';
            } else {
                echo "Aucun horaire trouvé.";
            }
        } else {
            require Chemins::VUES_ADMIN . 'v_connexion.inc.php';
        }
    }
}
