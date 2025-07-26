<?php
require_once 'application/modeles/gestion_famille.class.php';

class ControleurFamilles {
    public function gererFamilles() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }
        $familles = GestionFamille::getLesFamilles();
        $titrePage = "Gestion des Catégories";
        $messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
        $messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
        unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
        if (!defined('INDEX_PRINCIPAL')) {
            define('INDEX_PRINCIPAL', true);
        }
        
        // Utiliser le layout admin commun
        $pageActive = 'familles';
        
        // Capturer le contenu de la vue
        ob_start();
        include_once("application/vues/partie_admin/v_gestion_familles.inc.php");
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }

    public function ajouter() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $nomFamille = trim($_POST['nomFamille'] ?? '');
        
        if (empty($nomFamille)) {
            $this->repondreJSON(['succes' => false, 'message' => 'Le nom de la catégorie est requis']);
            return;
        }

        try {
            $resultat = GestionFamille::ajouterFamille($nomFamille);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Catégorie ajoutée avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de l\'ajout']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    public function modifier() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $idFamille = $_POST['idFamille'] ?? null;
        $nomFamille = trim($_POST['nomFamille'] ?? '');
        
        if (!$idFamille || empty($nomFamille)) {
            $this->repondreJSON(['succes' => false, 'message' => 'ID et nom de la catégorie requis']);
            return;
        }

        try {
            $resultat = GestionFamille::modifierFamille($idFamille, $nomFamille);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Catégorie modifiée avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de la modification']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    public function supprimer() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $idFamille = $_POST['idFamille'] ?? null;
        
        if (!$idFamille) {
            $this->repondreJSON(['succes' => false, 'message' => 'ID de la catégorie requis']);
            return;
        }

        try {
            $resultat = GestionFamille::supprimerFamille($idFamille);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Catégorie supprimée avec succès']);
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
?>

