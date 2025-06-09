<?php
require_once Chemins::MODELES . 'gestion_basededonnee.class.php';

class ControleurHoraires {

    public function __construct() {
        require_once Chemins::MODELES . 'gestion_horaires.class.php';
    }

    // Méthode pour afficher les horaires
    public function afficher() {
        $lesHoraires = GestionHoraires::getLesHoraires();
        if ($lesHoraires !== null) {
            VariablesGlobales::$lesHoraires = $lesHoraires;

            // Inclure la vue qui utilise la variable $lesHoraires
            require_once Chemins::VUES . 'v_horaires.inc.php';
        } else {
            // Gérer le cas où aucun horaire n'a été récupéré
            echo "Aucun horaire trouvé.";
        }
    }

    public function modifierLesHoraires() {
        // Récupérer la connexion PDO via la classe GestionBaseDeDonnees
        $pdo = GestionBaseDeDonnees::getConnexion();

        // Vérifier que les données sont envoyées par POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
            // Récupérer les horaires fermés et les horaires du matin/soir
            foreach ($_POST['idHoraire'] as $index => $idHoraire) {
                $ferme = isset($_POST['ferme'][$index]) ? 1 : 0;
                $ouvertureMatin = $_POST['ouvertureMatin'][$index] ?? null;
                $fermetureMatin = $_POST['fermetureMatin'][$index] ?? null;
                $ouvertureAprem = $_POST['ouvertureAprem'][$index] ?? null;
                $fermetureAprem = $_POST['fermetureAprem'][$index] ?? null;

                // Mise à jour dans la base de données
                $stmt = $pdo->prepare("UPDATE horaires SET 
                ferme = :ferme,
                heureOuvertureMatin = :ouvertureMatin,
                heureFermetureMatin = :fermetureMatin,
                heureOuvertureAprem = :ouvertureAprem,
                heureFermetureAprem = :fermetureAprem
                WHERE idHoraire = :idHoraire");
                $stmt->execute([
                    ':ferme' => $ferme,
                    ':ouvertureMatin' => $ouvertureMatin,
                    ':fermetureMatin' => $fermetureMatin,
                    ':ouvertureAprem' => $ouvertureAprem,
                    ':fermetureAprem' => $fermetureAprem,
                    ':idHoraire' => $idHoraire
                ]);
            }
            echo "Les horaires ont été mis à jour.";
        }
    }
}

?>
