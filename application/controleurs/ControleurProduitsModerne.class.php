<?php

/**
 * Contrôleur moderne pour la gestion des produits/services
 * @author TR Hair Design
 * @version 2.0
 */
class ControleurProduitsModerne {

    private $gestionnaire;

    public function __construct() {
        require_once Chemins::MODELES . 'gestion_produits_moderne.class.php';
        $this->gestionnaire = new GestionProduitsModerne();
    }

    /**
     * Liste tous les produits pour l'administration
     */
    public function listerProduits() {
        try {
            // Vérification de la session admin
            if (!$this->estConnecte()) {
                $this->redirigerVersConnexion();
                return;
            }

            // Récupération des données
            $produits = $this->gestionnaire->obtenirTousLesProduits();
            $statistiques = $this->gestionnaire->obtenirStatistiques();
            $familles = $this->gestionnaire->obtenirToutesLesFamilles();

            // Génération du token CSRF
            $token = $this->genererTokenCSRF();

            // Variables pour le layout admin
            $pageActive = 'produits';
            $titrePage = "Gestion des Services et Prestations";
            $messageSucces = $_SESSION['message_succes'] ?? null;
            $messageErreur = $_SESSION['message_erreur'] ?? null;
            
            // Nettoyage des messages
            unset($_SESSION['message_succes'], $_SESSION['message_erreur']);

            // Capturer le contenu de la vue
            ob_start();
            include_once("application/vues/partie_admin/v_gestion_produits.inc.php");
            $contenu = ob_get_clean();
            
            // Charger le layout avec le contenu
            require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';

        } catch (Exception $e) {
            $_SESSION['message_erreur'] = "Erreur lors du chargement : " . $e->getMessage();
            require_once Chemins::VUES . 'v_erreur404.inc.php';
        }
    }

    /**
     * Affiche le formulaire d'ajout d'un produit
     */
    public function ajouterProduit() {
        if (!$this->estConnecte()) {
            $this->redirigerVersConnexion();
            return;
        }

        try {
            $familles = $this->gestionnaire->obtenirToutesLesFamilles();
            
            $this->chargerVueAdmin('v_ajout_produit.inc.php', [
                'familles' => $familles,
                'action' => 'ajouter',
                'csrf_token' => $this->genererTokenCSRF(),
                'titrePage' => 'Ajouter un Service'
            ]);
        } catch (Exception $e) {
            $this->afficherErreur("Erreur : " . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de modification d'un produit
     */
    public function modifierProduit() {
        if (!$this->estConnecte()) {
            $this->redirigerVersConnexion();
            return;
        }

        $idProduit = $_GET['id'] ?? null;
        
        if (!$idProduit) {
            $this->afficherErreur("ID produit manquant.");
            return;
        }

        try {
            $produit = $this->gestionnaire->obtenirProduitParId($idProduit);
            $familles = $this->gestionnaire->obtenirToutesLesFamilles();
            
            if (!$produit) {
                $this->afficherErreur("Produit introuvable.");
                return;
            }

            $this->chargerVueAdmin('v_ajout_produit.inc.php', [
                'produit' => $produit,
                'familles' => $familles,
                'action' => 'modifier',
                'csrf_token' => $this->genererTokenCSRF(),
                'titrePage' => 'Modifier un Service'
            ]);
        } catch (Exception $e) {
            $this->afficherErreur("Erreur : " . $e->getMessage());
        }
    }

    /**
     * Sauvegarde un produit (ajout ou modification)
     */
    public function sauvegarderProduit() {
        if (!$this->estConnecte()) {
            $this->redirigerVersConnexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigerVersListe();
            return;
        }

        if (!$this->verifierTokenCSRF()) {
            $this->afficherErreur("Token de sécurité invalide.");
            return;
        }

        $validation = $this->validerDonneesProduit($_POST);
        
        if (!empty($validation['erreurs'])) {
            $this->afficherErreur("Erreurs de validation : " . implode(', ', $validation['erreurs']));
            return;
        }

        try {
            $idProduit = $_POST['idProduit'] ?? null;
            
            // Préparer les données pour le modèle
            $donneesProduit = [
                'nomProduit' => $validation['nomProduit'],
                'description' => $validation['description'],
                'prix' => $validation['prix'],
                'duree' => $validation['duree'],
                'unite' => $validation['unite'],
                'idFamille' => $validation['idFamille'],
                'actif' => $validation['actif'],
                'populaire' => $validation['populaire'],
                'position' => $validation['position']
            ];
            
            if ($idProduit) {
                // Modification
                $resultat = $this->gestionnaire->mettreAJourProduit($idProduit, $donneesProduit);
                $message = 'modifie';
            } else {
                // Ajout
                $resultat = $this->gestionnaire->ajouterProduit($donneesProduit);
                $message = 'ajoute';
            }
            
            if ($resultat) {
                $_SESSION['message_succes'] = "Service $message avec succès.";
                header("Location: index.php?controleur=ProduitsModerne&action=listerProduits");
                exit;
            } else {
                $this->afficherErreur("Erreur lors de la sauvegarde.");
            }
        } catch (Exception $e) {
            $this->afficherErreur("Erreur : " . $e->getMessage());
        }
    }

    /**
     * Supprime un produit (AJAX)
     */
    public function supprimerProduit() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $idProduit = $_POST['id'] ?? null;
        
        if (!$idProduit) {
            $this->repondreJSON(['succes' => false, 'message' => 'ID produit manquant']);
            return;
        }

        try {
            $resultat = $this->gestionnaire->supprimerProduit($idProduit);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Service supprimé avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de la suppression']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Active/désactive un produit (AJAX)
     */
    public function toggleActifProduit() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $idProduit = $_POST['id'] ?? null;
        
        if (!$idProduit) {
            $this->repondreJSON(['succes' => false, 'message' => 'ID produit manquant']);
            return;
        }

        try {
            $resultat = $this->gestionnaire->toggleActifProduit($idProduit);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Statut modifié avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de la modification']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    // ========== MÉTHODES PRIVÉES ==========

    /**
     * Valide les données d'un produit
     */
    private function validerDonneesProduit($donnees): array {
        $erreurs = [];
        $produitValide = [];

        // Nom du produit
        $produitValide['nomProduit'] = trim($donnees['nomProduit'] ?? '');
        if (empty($produitValide['nomProduit'])) {
            $erreurs[] = "Le nom du produit est requis";
        } elseif (strlen($produitValide['nomProduit']) > 100) {
            $erreurs[] = "Le nom du produit ne peut pas dépasser 100 caractères";
        }

        // Description
        $produitValide['description'] = trim($donnees['description'] ?? '');

        // Prix
        $produitValide['prix'] = floatval($donnees['prix'] ?? 0);
        if ($produitValide['prix'] < 0) {
            $erreurs[] = "Le prix ne peut pas être négatif";
        }

        // Durée
        $produitValide['duree'] = intval($donnees['duree'] ?? 30);
        if ($produitValide['duree'] <= 0) {
            $erreurs[] = "La durée doit être positive";
        }

        // Unité
        $produitValide['unite'] = trim($donnees['unite'] ?? 'prestation');

        // Famille
        $produitValide['idFamille'] = intval($donnees['idFamille'] ?? 0);
        if ($produitValide['idFamille'] <= 0) {
            $produitValide['idFamille'] = null;
        }

        // Statuts
        $produitValide['actif'] = isset($donnees['actif']) ? 1 : 0;
        $produitValide['populaire'] = isset($donnees['populaire']) ? 1 : 0;

        // Position
        $produitValide['position'] = intval($donnees['position'] ?? 0);

        return [
            'erreurs' => $erreurs,
            'nomProduit' => $produitValide['nomProduit'],
            'description' => $produitValide['description'],
            'prix' => $produitValide['prix'],
            'duree' => $produitValide['duree'],
            'unite' => $produitValide['unite'],
            'idFamille' => $produitValide['idFamille'],
            'actif' => $produitValide['actif'],
            'populaire' => $produitValide['populaire'],
            'position' => $produitValide['position']
        ];
    }

    /**
     * Génère un token CSRF
     */
    private function genererTokenCSRF(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie le token CSRF
     */
    private function verifierTokenCSRF($token = null): bool {
        $token = $token ?? $_POST['csrf_token'] ?? null;
        return $token && isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    private function estConnecte(): bool {
        return isset($_SESSION['login_admin']) && !empty($_SESSION['login_admin']);
    }

    /**
     * Charge une vue d'administration avec le layout admin
     */
    private function chargerVueAdmin(string $vue, array $donnees = []): void {
        // Variables pour le layout admin
        $pageActive = 'produits';
        $titrePage = $donnees['titrePage'] ?? 'Gestion des Services';
        $messageSucces = $_SESSION['message_succes'] ?? null;
        $messageErreur = $_SESSION['message_erreur'] ?? null;
        
        // Nettoyage des messages
        unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
        
        // Extraire les données pour la vue
        extract($donnees);
        
        // Capturer le contenu de la vue
        ob_start();
        $cheminVue = Chemins::VUES_ADMIN . $vue;
        
        if (file_exists($cheminVue)) {
            require $cheminVue;
        } else {
            echo "<div class='alert alert-error'>Vue introuvable : $vue</div>";
        }
        
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }

    /**
     * Affiche une erreur
     */
    private function afficherErreur(string $message): void {
        echo "<div style='padding: 50px; text-align: center; font-family: Arial, sans-serif; color: red;'>";
        echo "<h2>Erreur</h2>";
        echo "<p>" . htmlspecialchars($message) . "</p>";
        echo "<a href='index.php?controleur=ProduitsModerne&action=listerProduits'>Retour à la liste</a>";
        echo "</div>";
    }

    /**
     * Redirige vers la connexion
     */
    private function redirigerVersConnexion(): void {
        header("Location: index.php?controleur=Admin&action=afficherIndex");
        exit;
    }

    /**
     * Redirige vers la liste
     */
    private function redirigerVersListe(): void {
        header("Location: index.php?controleur=ProduitsModerne&action=listerProduits");
        exit;
    }

    /**
     * Répond en JSON
     */
    private function repondreJSON($data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>
