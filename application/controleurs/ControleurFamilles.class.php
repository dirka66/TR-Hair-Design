<?php
require('application/vues/partie_admin/v_gestionfamille.inc.php');

class ControleurFamilles {
    public function gererFamilles() {
        $familleModel = new Famille();
        $familles = $familleModel->getAllFamilles();

        require_once Chemins::VUES . 'v_gestionfamille.inc.php';
    }

    public function ajouter() {
        if (!empty($_POST['nomFamille'])) {
            $familleModel = new Famille();
            $familleModel->ajouterFamille($_POST['nomFamille']);
        }
        header("Location: index.php?controleur=Familles&action=gererFamilles");
        exit;
    }

    public function modifier() {
        if (!empty($_POST['idFamille']) && !empty($_POST['nomFamille'])) {
            $familleModel = new Famille();
            $familleModel->modifierFamille($_POST['idFamille'], $_POST['nomFamille']);
        }
        header("Location: index.php?controleur=Familles&action=gererFamilles");
        exit;
    }

    public function supprimer() {
        if (!empty($_POST['idFamille'])) {
            $familleModel = new Famille();
            $familleModel->supprimerFamille($_POST['idFamille']);
        }
        header("Location: index.php?controleur=Familles&action=gererFamilles");
        exit;
    }
}
?>

