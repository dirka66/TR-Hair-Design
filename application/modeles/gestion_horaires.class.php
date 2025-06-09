<?php

//require_once '../../configs/mysql_config.class.php';

class GestionHoraires {
    // <editor-fold defaultstate="collapsed" desc="Champs statiques"> 

    /**
     * Objet de la classe PDO
     * @var PDO
     */
    private static $pdoCnxBase = null;

    /**
     * Objet de la classe PDOStatement
     * @var PDOStatement
     */
    private static $pdoStResults = null;
    //private static $pdoStVerification = null;
    private static $requete = ""; //texte de la requête
    //private static $requeteVerification = "";
    private static $resultat = null; //résultat de la requête

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Méthodes statiques"> 

    /**
     * Permet de se connecter à la base de données
     */
    public static function seConnecter() {
        if (!isset(self::$pdoCnxBase)) { //S'il n'y a pas encore eu de connexion
            try {
                self::$pdoCnxBase = new PDO('mysql:host=' . MysqlConfig::SERVEUR . ';dbname=' . MysqlConfig::BASE, MysqlConfig::UTILISATEUR, MysqlConfig::MOT_DE_PASSE);

                self::$pdoCnxBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdoCnxBase->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                self::$pdoCnxBase->query("SET CHARACTER SET utf8");
            } catch (Exception $e) {
                // l’objet pdoCnxBase a généré automatiquement un objet de type Exception
                echo 'Erreur : ' . $e->getMessage() . '<br />'; // méthode de la classe Exception
                echo 'Code : ' . $e->getCode(); // méthode de la classe Exception                    
            }
        }
    }

    public static function seDeconnecter() {
        self::$pdoCnxBase = null;
        //si on n'appelle pas la méthode, la déconnexion a lieu en fin de script
    }

    public static function getLesHoraires() {
        self::seConnecter();

        self::$requete = "
        SELECT 
            idHoraire, 
            DATE_FORMAT(heureOuvertureMatin, '%H:%i') AS heureOuvertureMatin, 
            DATE_FORMAT(heureFermetureMatin, '%H:%i') AS heureFermetureMatin, 
            DATE_FORMAT(heureOuvertureAprem, '%H:%i') AS heureOuvertureAprem, 
            DATE_FORMAT(heureFermetureAprem, '%H:%i') AS heureFermetureAprem, 
            ferme 
        FROM horaire;
    ";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

// Méthode pour récupérer la connexion à la base de données
    public static function getDB() {
        // Appeler la méthode de connexion si elle n'a pas été faite
        self::seConnecter();
        return self::$pdoCnxBase; // Retourne la connexion
    }

    public static function getHoraireById($idHoraire) {
        self::seConnecter();

        self::$requete = "SELECT idHoraire, DATE_FORMAT(heureOuvertureMatin, '%H:%i') AS heureOuvertureMatin, DATE_FORMAT(heureFermetureMatin, '%H:%i') AS heureFermetureMatin, DATE_FORMAT(heureOuvertureAprem, '%H:%i') AS heureOuvertureAprem, DATE_FORMAT(heureFermetureAprem, '%H:%i') AS heureFermetureAprem FROM horaire WHERE idHoraire =:idHoraire";

        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('idHoraire', $idHoraire);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    public function modifierLesHoraires() {
        if (isset($_POST['modifier'])) {
            // Récupération des données du formulaire
            $idHoraires = $_POST['idHoraire'];

            // Valeurs pour chaque jour de la semaine
            $fermeLundi = isset($_POST['fermeLundi']) ? 1 : 0;
            $fermeMardi = isset($_POST['fermeMardi']) ? 1 : 0;
            $fermeMercredi = isset($_POST['fermeMercredi']) ? 1 : 0;
            $fermeJeudi = isset($_POST['fermeJeudi']) ? 1 : 0;
            $fermeVendredi = isset($_POST['fermeVendredi']) ? 1 : 0;
            $fermeSamedi = isset($_POST['fermeSamedi']) ? 1 : 0;
            $fermeDimanche = isset($_POST['fermeDimanche']) ? 1 : 0;

            // Récupération des horaires
            $ouvertureMatin = $_POST['ouvertureMatin'];
            $fermetureMatin = $_POST['fermetureMatin'];
            $ouvertureAprem = $_POST['ouvertureAprem'];
            $fermetureAprem = $_POST['fermetureAprem'];

            foreach ($idHoraires as $index => $idHoraire) {
                // Mettre à jour les horaires de chaque jour avec leur propre statut de fermeture
                $sql = "UPDATE horaire SET
                    heureOuvertureMatin = :ouvertureMatin,
                    heureFermetureMatin = :fermetureMatin,
                    heureOuvertureAprem = :ouvertureAprem,
                    heureFermetureAprem = :fermetureAprem,
                    fermeLundi = :fermeLundi,
                    fermeMardi = :fermeMardi,
                    fermeMercredi = :fermeMercredi,
                    fermeJeudi = :fermeJeudi,
                    fermeVendredi = :fermeVendredi,
                    fermeSamedi = :fermeSamedi,
                    fermeDimanche = :fermeDimanche
                WHERE idHoraire = :idHoraire";

                $stmt = self::getDB()->prepare($sql);
                $stmt->execute([
                    ':ouvertureMatin' => $ouvertureMatin[$index] ?? null,
                    ':fermetureMatin' => $fermetureMatin[$index] ?? null,
                    ':ouvertureAprem' => $ouvertureAprem[$index] ?? null,
                    ':fermetureAprem' => $fermetureAprem[$index] ?? null,
                    ':fermeLundi' => $fermeLundi,
                    ':fermeMardi' => $fermeMardi,
                    ':fermeMercredi' => $fermeMercredi,
                    ':fermeJeudi' => $fermeJeudi,
                    ':fermeVendredi' => $fermeVendredi,
                    ':fermeSamedi' => $fermeSamedi,
                    ':fermeDimanche' => $fermeDimanche,
                    ':idHoraire' => $idHoraire
                ]);
            }

            header('Location: index.php?controleur=Horaires&action=modifierLesHoraires');
            exit;
        }
    }

    public static function getNbProduits() {
        self::seConnecter();

        self::$requete = "SELECT Count(*) AS nbProduits FROM produit";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();

        self::$pdoStResults->closeCursor();

        return self::$resultat->nbCategories;
    }

    public static function updateHoraire($idHoraire, $heureOuvertureMatin, $heureFermetureMatin, $heureOuvertureAprem, $heureFermetureAprem, $fermeLundi) {
        // Récupérer la connexion à la base de données
        $pdo = self::getDB();

        // Exemple d'exécution d'une requête UPDATE
        $requete = "UPDATE horaire SET heureOuvertureMatin = ?, heureFermetureMatin = ?, heureOuvertureAprem = ?, heureFermetureAprem = ?, ferme = ? WHERE idHoraire = ?";
        $stmt = $pdo->prepare($requete);
        $stmt->execute([$heureOuvertureMatin, $heureFermetureMatin, $heureOuvertureAprem, $heureFermetureAprem, $fermeLundi, $idHoraire]);
    }

    // </editor-fold>  
}

//Tests

//GestionProduits::seConnecter();
//var_dump(GestionProduits::getLesProduits());

//$lesProduits = GestionProduits::getLesProduits();
//foreach($lesProduits as $produit){
//    echo $produit->nomProduit;
//}

//$lesCategories = GestionCategorie::getCategorieById(1);
//var_dump($lesCategories);

//$lesHoraires = GestionHoraires::modifierHoraire('Lundi', '09:10:00', '10:10:00', '15:05:00', '19:15:00');
//var_dump($lesHoraires);
//$lesCategories = GestionCategorie::ajouterCategorie('$nomProduit');
//var_dump($lesCategories);

//$lesCategories = GestionCategorie::supprimerCategorie('$nomProduit');
//var_dump($lesCategories);