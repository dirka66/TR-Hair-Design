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
}
