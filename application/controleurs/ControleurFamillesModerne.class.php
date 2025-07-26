<?php

/**
 * Contrôleur moderne pour la gestion des familles (catégories de services)
 * @author TR Hair Design
 * @version 2.0
 */
class ControleurFamillesModerne {
    
    private $modele;

    public function __construct() {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        require_once Chemins::MODELES . 'gestion_familles_moderne.class.php';
        $this->modele = new GestionFamillesModerne();
    }

    /**
     * Affiche la liste des familles
     */
    public function listerFamilles() {
        try {
            // Vérification de la session admin
            if (!$this->estAdminConnecte()) {
                $this->redirigerVersConnexion();
                return;
            }

            // Récupération des données
            $familles = $this->modele->obtenirToutesLesFamilles();
            $statistiques = $this->modele->obtenirStatistiques();

            // Variables pour la vue
            $titrePage = "Gestion des Catégories de Services";
            $messageSucces = $_SESSION['message_succes'] ?? null;
            $messageErreur = $_SESSION['message_erreur'] ?? null;
            
            // Nettoyage des messages
            unset($_SESSION['message_succes'], $_SESSION['message_erreur']);

            // Variables pour le layout admin
            $pageActive = 'familles';
            
            // Définir INDEX_PRINCIPAL pour la sécurité
            if (!defined('INDEX_PRINCIPAL')) {
                define('INDEX_PRINCIPAL', true);
            }
            
            // Capturer le contenu de la vue
            ob_start();
            include_once("application/vues/partie_admin/v_gestion_familles.inc.php");
            $contenu = ob_get_clean();
            
            // Charger le layout avec le contenu
            require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';

        } catch (Exception $e) {
            $_SESSION['message_erreur'] = "Erreur lors du chargement : " . $e->getMessage();
            require_once Chemins::VUES . 'v_erreur404.inc.php';
        }
    }

    /**
     * Méthode d'ajout pour compatibilité avec l'ancien système
     */
    public function ajouter() {
        if (!$this->estAdminConnecte()) {
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
            $donnees = [
                'nomFamille' => $nomFamille,
                'description' => '',
                'icone' => 'fas fa-cut',
                'couleur' => '#4e73df',
                'actif' => 1
            ];
            
            $resultat = $this->modele->ajouterFamille($donnees);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Catégorie ajoutée avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de l\'ajout']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Méthode de modification pour compatibilité avec l'ancien système
     */
    public function modifier() {
        if (!$this->estAdminConnecte()) {
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
            // Récupérer les données actuelles de la famille
            $familleActuelle = $this->modele->obtenirFamilleParId($idFamille);
            
            if (!$familleActuelle) {
                $this->repondreJSON(['succes' => false, 'message' => 'Catégorie non trouvée']);
                return;
            }
            
            $donnees = [
                'nomFamille' => $nomFamille,
                'description' => $familleActuelle->description ?? '',
                'icone' => $familleActuelle->icone ?? 'fas fa-cut',
                'couleur' => $familleActuelle->couleur ?? '#4e73df',
                'actif' => $familleActuelle->actif ?? 1
            ];
            
            $resultat = $this->modele->mettreAJourFamille($idFamille, $donnees);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Catégorie modifiée avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de la modification']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Méthode de suppression pour compatibilité avec l'ancien système
     */
    public function supprimer() {
        if (!$this->estAdminConnecte()) {
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
            // Vérifier s'il y a des produits dans cette famille
            $nombreProduits = $this->modele->compterProduitsParFamille($idFamille);
            
            if ($nombreProduits > 0) {
                $this->repondreJSON(['succes' => false, 'message' => 'Impossible de supprimer cette catégorie car elle contient des services']);
                return;
            }
            
            $resultat = $this->modele->supprimerFamille($idFamille);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Catégorie supprimée avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de la suppression']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Active/désactive une famille
     */
    public function toggleActif() {
        try {
            if (!$this->estAdminConnecte()) {
                echo json_encode(['succes' => false, 'message' => 'Non autorisé']);
                return;
            }

            $idFamille = $_POST['id'] ?? null;
            
            if (!$idFamille || !$this->validerIdEntier($idFamille)) {
                echo json_encode(['succes' => false, 'message' => 'ID invalide']);
                return;
            }

            // Vérification du token CSRF
            if (!$this->verifierTokenCSRF($_POST['csrf_token'] ?? '')) {
                echo json_encode(['succes' => false, 'message' => 'Token invalide']);
                return;
            }

            $resultat = $this->modele->toggleActifFamille($idFamille);

            if ($resultat) {
                echo json_encode(['succes' => true, 'message' => 'Statut modifié avec succès']);
            } else {
                echo json_encode(['succes' => false, 'message' => 'Erreur lors de la modification']);
            }

        } catch (Exception $e) {
            echo json_encode(['succes' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Valide les données d'une famille
     */
    private function validerDonneesFamille($donnees) {
        $erreurs = [];

        // Nom de famille
        if (empty($donnees['nomFamille'])) {
            $erreurs[] = "Le nom de la catégorie est obligatoire.";
        } elseif (strlen($donnees['nomFamille']) > 100) {
            $erreurs[] = "Le nom de la catégorie ne peut pas dépasser 100 caractères.";
        }

        // Description
        if (strlen($donnees['description'] ?? '') > 500) {
            $erreurs[] = "La description ne peut pas dépasser 500 caractères.";
        }

        // Icône
        if (empty($donnees['icone'])) {
            $erreurs[] = "L'icône est obligatoire.";
        }

        // Couleur
        if (empty($donnees['couleur'])) {
            $erreurs[] = "La couleur est obligatoire.";
        } elseif (!preg_match('/^#[0-9A-Fa-f]{6}$/', $donnees['couleur'])) {
            $erreurs[] = "Format de couleur invalide.";
        }

        return [
            'valide' => empty($erreurs),
            'erreur' => implode(' ', $erreurs)
        ];
    }

    /**
     * Vérifie si l'admin est connecté
     */
    private function estAdminConnecte() {
        return isset($_SESSION['login_admin']) && !empty($_SESSION['login_admin']);
    }

    /**
     * Redirige vers la page de connexion
     */
    private function redirigerVersConnexion() {
        header("Location: index.php?controleur=Admin&action=connexion");
        exit();
    }

    /**
     * Génère un token CSRF
     */
    private function genererTokenCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie le token CSRF
     */
    private function verifierTokenCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Valide un ID entier
     */
    private function validerIdEntier($id) {
        return filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false;
    }

    /**
     * Méthode pour répondre en JSON
     */
    private function repondreJSON($data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>
