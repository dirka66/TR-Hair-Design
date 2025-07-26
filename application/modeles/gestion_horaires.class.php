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
    private static $requete = ""; //texte de la requête
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

    /**
     * Récupère tous les horaires
     */
    public static function getLesHoraires() {
        self::seConnecter();

        self::$requete = "
        SELECT 
            idHoraire, 
            jour,
            numeroJour,
            DATE_FORMAT(heureOuvertureMatin, '%H:%i') AS heureOuvertureMatin, 
            DATE_FORMAT(heureFermetureMatin, '%H:%i') AS heureFermetureMatin, 
            DATE_FORMAT(heureOuvertureAprem, '%H:%i') AS heureOuvertureAprem, 
            DATE_FORMAT(heureFermetureAprem, '%H:%i') AS heureFermetureAprem, 
            ferme,
            pauseMidi
        FROM horaire
        ORDER BY numeroJour ASC;
        ";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    /**
     * Récupère la connexion à la base de données
     */
    public static function getDB() {
        // Appeler la méthode de connexion si elle n'a pas été faite
        self::seConnecter();
        return self::$pdoCnxBase; // Retourne la connexion
    }

    /**
     * Récupère un horaire par son ID
     */
    public static function getHoraireById($idHoraire) {
        self::seConnecter();

        self::$requete = "SELECT 
            idHoraire, 
            jour,
            numeroJour,
            DATE_FORMAT(heureOuvertureMatin, '%H:%i') AS heureOuvertureMatin, 
            DATE_FORMAT(heureFermetureMatin, '%H:%i') AS heureFermetureMatin, 
            DATE_FORMAT(heureOuvertureAprem, '%H:%i') AS heureOuvertureAprem, 
            DATE_FORMAT(heureFermetureAprem, '%H:%i') AS heureFermetureAprem,
            ferme,
            pauseMidi
        FROM horaire 
        WHERE idHoraire = :idHoraire";

        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('idHoraire', $idHoraire);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    /**
     * Met à jour un horaire
     */
    public static function updateHoraire($idHoraire, $heureOuvertureMatin, $heureFermetureMatin, $heureOuvertureAprem, $heureFermetureAprem, $ferme) {
        try {
            // Récupérer la connexion à la base de données
            $pdo = self::getDB();
            
            $requete = "UPDATE horaire SET 
                heureOuvertureMatin = :ouvertureMatin, 
                heureFermetureMatin = :fermetureMatin, 
                heureOuvertureAprem = :ouvertureAprem, 
                heureFermetureAprem = :fermetureAprem, 
                ferme = :ferme 
                WHERE idHoraire = :idHoraire";
                
            $stmt = $pdo->prepare($requete);
            $result = $stmt->execute([
                ':ouvertureMatin' => $heureOuvertureMatin,
                ':fermetureMatin' => $heureFermetureMatin,
                ':ouvertureAprem' => $heureOuvertureAprem,
                ':fermetureAprem' => $heureFermetureAprem,
                ':ferme' => $ferme,
                ':idHoraire' => $idHoraire
            ]);
            
            return $result;
        } catch (Exception $e) {
            error_log("Erreur updateHoraire: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour un horaire avec gestion d'erreur améliorée
     */
    public static function updateHoraireWithErrorHandling($idHoraire, $heureOuvertureMatin, $heureFermetureMatin, $heureOuvertureAprem, $heureFermetureAprem, $ferme) {
        try {
            $pdo = self::getDB();
            
            $requete = "UPDATE horaire SET 
                heureOuvertureMatin = :ouvertureMatin, 
                heureFermetureMatin = :fermetureMatin, 
                heureOuvertureAprem = :ouvertureAprem, 
                heureFermetureAprem = :fermetureAprem, 
                ferme = :ferme 
                WHERE idHoraire = :idHoraire";
                
            $stmt = $pdo->prepare($requete);
            $result = $stmt->execute([
                ':ouvertureMatin' => $heureOuvertureMatin,
                ':fermetureMatin' => $heureFermetureMatin,
                ':ouvertureAprem' => $heureOuvertureAprem,
                ':fermetureAprem' => $heureFermetureAprem,
                ':ferme' => $ferme,
                ':idHoraire' => $idHoraire
            ]);
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                throw new Exception("Erreur SQL: " . implode(", ", $errorInfo));
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Erreur updateHoraireWithErrorHandling: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Récupère les statistiques des horaires
     */
    public static function getStatistiquesHoraires() {
        self::seConnecter();
        
        $stats = new stdClass();
        
        // Total des jours
        self::$requete = "SELECT COUNT(*) as total FROM horaire";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        $resultat = self::$pdoStResults->fetch();
        $stats->totalJours = $resultat->total;
        
        // Jours ouverts
        self::$requete = "SELECT COUNT(*) as ouverts FROM horaire WHERE ferme = 0";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        $resultat = self::$pdoStResults->fetch();
        $stats->joursOuverts = $resultat->ouverts;
        
        // Jours fermés
        self::$requete = "SELECT COUNT(*) as fermes FROM horaire WHERE ferme = 1";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        $resultat = self::$pdoStResults->fetch();
        $stats->joursFermes = $resultat->fermes;
        
        // Heures moyennes par jour (calcul approximatif)
        self::$requete = "SELECT AVG(
            TIMESTAMPDIFF(HOUR, heureOuvertureMatin, heureFermetureMatin) +
            TIMESTAMPDIFF(HOUR, heureOuvertureAprem, heureFermetureAprem)
        ) as heuresMoyennes FROM horaire WHERE ferme = 0";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        $resultat = self::$pdoStResults->fetch();
        $stats->heuresMoyennes = round($resultat->heuresMoyennes ?? 0, 1);
        
        self::$pdoStResults->closeCursor();
        
        return $stats;
    }

    // </editor-fold>  
}
?>