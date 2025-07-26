<?php

//require_once '../../configs/mysql_config.class.php';

class GestionInformations {
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
                // l'objet pdoCnxBase a généré automatiquement un objet de type Exception
                echo 'Erreur : ' . $e->getMessage() . '<br />'; // méthode de la classe Exception
                echo 'Code : ' . $e->getCode(); // méthode de la classe Exception                    
            }
        }
    }

    public static function seDeconnecter() {
        self::$pdoCnxBase = null;
        //si on n'appelle pas la méthode, la déconnexion a lieu en fin de script
    }

    public static function getLesInformations() {
        self::seConnecter();

        self::$requete = "SELECT * FROM information ORDER BY dateCreation DESC";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    /**
     * Ajoute une nouvelle information
     * @param string $titre Titre de l'information
     * @param string $contenu Contenu de l'information
     * @param int $important Si l'information est importante (1) ou non (0)
     * @return bool
     */
    public static function ajouterInformation($titre, $contenu, $important = 0) {
        try {
            self::seConnecter();

            self::$requete = "INSERT INTO information (titreInformation, libelleInformation, important, dateCreation) VALUES (:titre, :contenu, :important, NOW())";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindValue(':titre', $titre);
            self::$pdoStResults->bindValue(':contenu', $contenu);
            self::$pdoStResults->bindValue(':important', $important);
            self::$pdoStResults->execute();

            return self::$pdoStResults->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de l'information : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une information
     * @param int $idInformation ID de l'information à supprimer
     * @return bool
     */
    public static function supprimerInformation($idInformation) {
        try {
            self::seConnecter();

            self::$requete = "DELETE FROM information WHERE idInfo = :idInformation";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindValue(':idInformation', $idInformation);
            self::$pdoStResults->execute();

            return self::$pdoStResults->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'information : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Modifie une information existante
     * @param int $idInformation ID de l'information
     * @param string $titre Nouveau titre
     * @param string $contenu Nouveau contenu
     * @param int $important Si l'information est importante (1) ou non (0)
     * @return bool
     */
    public static function modifierInformation($idInformation, $titre, $contenu, $important = 0) {
        try {
            self::seConnecter();

            self::$requete = "UPDATE information SET titreInformation = :titre, libelleInformation = :contenu, important = :important, dateModification = NOW() WHERE idInfo = :idInformation";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindValue(':idInformation', $idInformation);
            self::$pdoStResults->bindValue(':titre', $titre);
            self::$pdoStResults->bindValue(':contenu', $contenu);
            self::$pdoStResults->bindValue(':important', $important);
            self::$pdoStResults->execute();

            return self::$pdoStResults->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification de l'information : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère une information par son ID
     * @param int $idInformation ID de l'information
     * @return object|null
     */
    public static function getInformationById($idInformation) {
        try {
            self::seConnecter();

            self::$requete = "SELECT * FROM information WHERE idInfo = :idInformation";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindValue(':idInformation', $idInformation);
            self::$pdoStResults->execute();
            self::$resultat = self::$pdoStResults->fetch();

            self::$pdoStResults->closeCursor();
            return self::$resultat;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'information : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère le nombre total d'informations
     * @return int
     */
    public static function getNbInformations() {
        try {
            self::seConnecter();

            self::$requete = "SELECT COUNT(*) AS nbInformations FROM information";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->execute();
            self::$resultat = self::$pdoStResults->fetch();

            self::$pdoStResults->closeCursor();
            return self::$resultat->nbInformations ?? 0;
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des informations : " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère les statistiques des informations
     * @return object
     */
    public static function obtenirStatistiques() {
        try {
            self::seConnecter();

            $stats = new stdClass();
            
            // Total des informations
            self::$requete = "SELECT COUNT(*) as total FROM information";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->execute();
            $result = self::$pdoStResults->fetch();
            $stats->totalInformations = $result->total ?? 0;

            // Informations importantes
            self::$requete = "SELECT COUNT(*) as importantes FROM information WHERE important = 1";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->execute();
            $result = self::$pdoStResults->fetch();
            $stats->informationsImportantes = $result->importantes ?? 0;

            // Informations récentes (moins de 7 jours)
            self::$requete = "SELECT COUNT(*) as recentes FROM information WHERE dateCreation >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->execute();
            $result = self::$pdoStResults->fetch();
            $stats->informationsRecentes = $result->recentes ?? 0;

            return $stats;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques : " . $e->getMessage());
            $stats = new stdClass();
            $stats->totalInformations = 0;
            $stats->informationsImportantes = 0;
            $stats->informationsRecentes = 0;
            return $stats;
        }
    }

    // </editor-fold>  
}
?>