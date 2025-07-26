<?php

//require_once '../../configs/mysql_config.class.php';

class GestionFamille {
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

    public static function getLesFamilles() {

        self::seConnecter();

        self::$requete = "SELECT * FROM famille;";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    /**
     * Ajoute une ligne dans la table Catégorie
     * @param type $libelleCateg Libellé de la Catégorie
     */
    public static function ajouterProduit($position, $nomProduit, $prix, $unite, $idFamille) {
        try {
            self::seConnecter();

            self::$requete = "INSERT INTO produit (position, nomProduit, prix, unite, idFamille) VALUES (:position, :nomProduit, :prix, :unite, :idFamille)";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindValue(':position', $position);
            self::$pdoStResults->bindValue(':nomProduit', $nomProduit);
            self::$pdoStResults->bindValue(':prix', $prix);
            self::$pdoStResults->bindValue(':unite', $unite);
            self::$pdoStResults->bindValue(':idFamille', $idFamille);
            self::$pdoStResults->execute();

            // Vérifier si l'insertion a réussi
            if (self::$pdoStResults->rowCount() > 0) {
                return true; // L'insertion a réussi
            } else {
                return false; // Aucune ligne n'a été insérée, probablement une erreur
            }
        } catch (PDOException $e) {
            // Gérer l'exception en affichant un message d'erreur ou en journalisant l'erreur
            error_log("Erreur lors de l'ajout du produit : " . $e->getMessage());
            return false; // Indiquer que l'ajout a échoué en raison de l'erreur
        }
    }

    public static function supprimerProduit($idProduit) {

        self::seConnecter();

        self::$requete = "delete from produit where idProduit =:idProduit";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('idProduit', $idProduit);
        self::$pdoStResults->execute();

        // Vérifier si l'insertion a réussi
        if (self::$pdoStResults->rowCount() > 0) {
            return true; // L'insertion a réussi
        } else {
            return false; // Aucune ligne n'a été insérée, probablement une erreur
        }
    }

    public static function modifierProduit($idProduit, $positionNouvelle, $nomNouveau, $prixNouveau, $uniteNouvelle, $idFamilleNouvelle) {
        self::seConnecter();
        try {
            self::$requete = "update produit set postion=:positionNouvelle, nomProduit=:nomNouveau, prix=:prixNouveau, unite=:uniteNouvelle, idFamille=:idFamilleNouvelle where idProduit=:idProduit";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindValue(':idProduit', $idProduit);
            self::$pdoStResults->bindValue(':positionNouvelle', $positionNouvelle);
            self::$pdoStResults->bindValue(':nomNouveau', $nomNouveau);
            self::$pdoStResults->bindValue(':prixNouveau', $prixNouveau);
            self::$pdoStResults->bindValue(':uniteNouvelle', $uniteNouvelle);
            self::$pdoStResults->bindValue(':idFamilleNouvelle', $idFamilleNouvelle);
            self::$pdoStResults->execute();

            // Vérifier si la modification a réussi
            if (self::$pdoStResults->rowCount() > 0) {
                return true; // La modification a réussi
            } else {
                return false; // Aucune ligne n'a été modifiée, probablement une erreur
            }
        } catch (PDOException $e) {
            // Gérer l'exception
            if ($e->getCode() == '23000') { // Code d'erreur pour violation de contrainte d'unicité
                // Le nouveau libellé est en conflit avec un libellé existant
                return false;
            } else {
                // Autre erreur
                error_log("Erreur lors de la modification du produit : " . $e->getMessage());
                return false; // Indiquer que la modification a échoué en raison de l'erreur
            }
        }
    }

    public static function getProduitById($idProduit) {
        self::seConnecter();

        self::$requete = "SELECT * FROM produit WHERE idProduit =:idProduit";

        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue('idProduit', $idProduit);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();

        self::$pdoStResults->closeCursor();

        return self::$resultat;
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

    public static function ajouterFamille($nomFamille) {
        self::seConnecter();
        self::$requete = "INSERT INTO famille (nomFamille) VALUES (:nomFamille)";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue(':nomFamille', $nomFamille);
        return self::$pdoStResults->execute();
    }

    public static function modifierFamille($idFamille, $nomFamille) {
        self::seConnecter();
        self::$requete = "UPDATE famille SET nomFamille = :nomFamille WHERE idFamille = :idFamille";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue(':nomFamille', $nomFamille);
        self::$pdoStResults->bindValue(':idFamille', $idFamille);
        return self::$pdoStResults->execute();
    }

    public static function supprimerFamille($idFamille) {
        self::seConnecter();
        self::$requete = "DELETE FROM famille WHERE idFamille = :idFamille";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindValue(':idFamille', $idFamille);
        return self::$pdoStResults->execute();
    }

    // </editor-fold>  
}
?>