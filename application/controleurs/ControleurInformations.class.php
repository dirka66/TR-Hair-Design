<?php

class ControleurInformations {

    public function __construct() {
        require_once Chemins::MODELES . 'gestion_informations.class.php';
    }

    public function afficher() {
        // Récupérer les produits depuis le modèle
        $lesInformations = GestionInformations::getLesInformations();
        if ($lesInformations !== null) {
            VariablesGlobales::$lesInformations = $lesInformations;

            // Inclure la vue qui utilise la variable $lesProduits
            require_once Chemins::VUES . 'v_informations.inc.php';
        } else {
            // Gérer le cas où aucun produit n'a été récupéré
            echo "Aucune information trouvée.";
        }
    }

    public function listerInformations() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }
        
        // Récupérer les informations
        $lesInformations = GestionInformations::getLesInformations();
        
        // Récupérer les statistiques
        $statistiques = GestionInformations::obtenirStatistiques();
        
        // Messages de session
        $messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
        $messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
        unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
        
        if (!defined('INDEX_PRINCIPAL')) {
            define('INDEX_PRINCIPAL', true);
        }
        
        // Variables pour le layout admin
        $pageActive = 'informations';
        $titrePage = 'Gestion des Actualités - Administration';
        
        // Capturer le contenu de la vue
        ob_start();
        include_once("application/vues/partie_admin/v_gestion_informations.inc.php");
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }

    public function ajouterInformation() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $important = isset($_POST['important']) ? 1 : 0;
        
        if (empty($titre) || empty($contenu)) {
            $this->repondreJSON(['succes' => false, 'message' => 'Titre et contenu requis']);
            return;
        }

        try {
            $resultat = GestionInformations::ajouterInformation($titre, $contenu, $important);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Actualité ajoutée avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de l\'ajout']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    public function modifierInformation() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $idInformation = $_POST['idInformation'] ?? null;
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $important = isset($_POST['important']) ? 1 : 0;
        
        if (!$idInformation || empty($titre) || empty($contenu)) {
            $this->repondreJSON(['succes' => false, 'message' => 'ID, titre et contenu requis']);
            return;
        }

        try {
            $resultat = GestionInformations::modifierInformation($idInformation, $titre, $contenu, $important);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Actualité modifiée avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de la modification']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    public function supprimerInformation() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $idInformation = $_POST['idInformation'] ?? null;
        
        if (!$idInformation) {
            $this->repondreJSON(['succes' => false, 'message' => 'ID de l\'actualité requis']);
            return;
        }

        try {
            $resultat = GestionInformations::supprimerInformation($idInformation);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Actualité supprimée avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de la suppression']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    private function estConnecte(): bool {
        return isset($_SESSION['login_admin']) && !empty($_SESSION['login_admin']);
    }

    private function repondreJSON($data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
