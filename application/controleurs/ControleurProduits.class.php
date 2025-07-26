<?php

class ControleurProduits {

    public function __construct() {
        require_once Chemins::MODELES . 'gestion_produits.class.php';
    }

    public function afficher() {
        // Récupérer les produits depuis le modèle
        $lesProduits = GestionProduits::getLesProduits();
        if ($lesProduits !== null) {
            VariablesGlobales::$lesProduits = $lesProduits;

            // Inclure la vue qui utilise la variable $lesProduits
            require_once Chemins::VUES . 'v_produits.inc.php';
        } else {
            // Gérer le cas où aucun produit n'a été récupéré
            echo "Aucun produit trouvé.";
        }
    }

    public function ajouter() {
        file_put_contents('test_trace.txt', 'Passe dans ajouter'.PHP_EOL, FILE_APPEND);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }
        require_once Chemins::MODELES . 'gestion_famille.class.php';
        $familles = GestionFamille::getLesFamilles();
        $messageErreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nomProduit = trim($_POST['nomProduit'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $prix = floatval($_POST['prix'] ?? 0);
            $duree = intval($_POST['duree'] ?? 0);
            $idFamille = intval($_POST['idFamille'] ?? 0);
            $position = intval($_POST['position'] ?? 0);
            $actif = isset($_POST['actif']) ? intval($_POST['actif']) : 1;
            $populaire = isset($_POST['populaire']) ? intval($_POST['populaire']) : 0;
            if ($nomProduit && $prix > 0 && $duree > 0 && $idFamille > 0) {
                require_once Chemins::MODELES . 'gestion_produits.class.php';
                $result = GestionProduits::ajouterProduit($position, $nomProduit, $prix, $duree, $idFamille, $description, $actif, $populaire);
                if ($result) {
                    $_SESSION['message_succes'] = "Service ajouté avec succès.";
                    header("Location: index.php?controleur=Produits&action=listerProduits");
                    exit();
                } else {
                    $messageErreur = "Erreur lors de l'ajout du service.";
                }
            } else {
                $messageErreur = "Veuillez remplir tous les champs obligatoires.";
            }
        }
        if (!defined('INDEX_PRINCIPAL')) {
            define('INDEX_PRINCIPAL', true);
        }
        $token = '';
        include_once("application/vues/partie_admin/v_ajout_produit.inc.php");
    }

    public function listerProduits() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }
        
        // Récupérer les produits
        $lesProduits = GestionProduits::getLesProduits();
        
        // Récupérer les familles pour les filtres
        require_once Chemins::MODELES . 'gestion_famille.class.php';
        $familles = GestionFamille::getLesFamilles();
        
        // Messages de session
        $messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
        $messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
        unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
        
        if (!defined('INDEX_PRINCIPAL')) {
            define('INDEX_PRINCIPAL', true);
        }
        
        // Variables pour le layout admin
        $pageActive = 'produits';
        $titrePage = 'Gestion des Services - Administration';
        
        // Calculer les statistiques
        $statistiques = [
            'total' => count($lesProduits),
            'actifs' => count(array_filter($lesProduits, function($p) { return $p->actif == 1; })),
            'populaires' => count(array_filter($lesProduits, function($p) { return $p->populaire == 1; })),
            'prixMoyen' => array_sum(array_column($lesProduits, 'prix')) / count($lesProduits)
        ];
        
        // Capturer le contenu de la vue
        ob_start();
        include_once("application/vues/partie_admin/v_gestion_produits.inc.php");
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }
}
