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
}
