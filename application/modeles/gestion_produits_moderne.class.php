<?php

/**
 * Modèle moderne pour la gestion des produits/services
 * @author TR Hair Design
 * @version 2.0
 */
class GestionProduitsModerne {
    
    private $connexion;

    public function __construct() {
        require_once Chemins::CONFIGS . 'mysql_config.class.php';
        $this->seConnecter();
    }

    /**
     * Établit la connexion à la base de données
     */
    private function seConnecter() {
        try {
            $this->connexion = new PDO(
                'mysql:host=' . MysqlConfig::SERVEUR . ';dbname=' . MysqlConfig::BASE,
                MysqlConfig::UTILISATEUR,
                MysqlConfig::MOT_DE_PASSE,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
        } catch (Exception $e) {
            throw new Exception("Erreur de connexion : " . $e->getMessage());
        }
    }

    /**
     * Récupère tous les produits
     */
    public function obtenirTousLesProduits($actifSeulement = false) {
        try {
            $whereClause = $actifSeulement ? "WHERE p.actif = 1" : "";
            
            $requete = "
                SELECT 
                    p.idProduit,
                    p.position,
                    p.nomProduit,
                    p.description,
                    p.prix,
                    p.duree,
                    p.unite,
                    p.actif,
                    p.populaire,
                    p.idFamille,
                    f.nomFamille,
                    f.icone as famille_icone,
                    f.couleur as famille_couleur
                FROM produit p
                LEFT JOIN famille f ON p.idFamille = f.idFamille
                $whereClause
                ORDER BY p.position ASC, p.nomProduit ASC
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des produits : " . $e->getMessage());
        }
    }

    /**
     * Récupère un produit par son ID
     */
    public function obtenirProduitParId($idProduit) {
        try {
            $requete = "
                SELECT 
                    p.idProduit,
                    p.position,
                    p.nomProduit,
                    p.description,
                    p.prix,
                    p.duree,
                    p.unite,
                    p.actif,
                    p.populaire,
                    p.idFamille,
                    f.nomFamille,
                    f.icone as famille_icone,
                    f.couleur as famille_couleur
                FROM produit p
                LEFT JOIN famille f ON p.idFamille = f.idFamille
                WHERE p.idProduit = :idProduit
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idProduit', $idProduit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération du produit : " . $e->getMessage());
        }
    }

    /**
     * Récupère les produits par famille
     */
    public function obtenirProduitsParFamille($idFamille, $actifSeulement = true) {
        try {
            $whereClause = $actifSeulement ? "AND p.actif = 1" : "";
            
            $requete = "
                SELECT 
                    p.idProduit,
                    p.position,
                    p.nomProduit,
                    p.description,
                    p.prix,
                    p.duree,
                    p.unite,
                    p.actif,
                    p.populaire
                FROM produit p
                WHERE p.idFamille = :idFamille $whereClause
                ORDER BY p.position ASC, p.nomProduit ASC
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idFamille', $idFamille, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des produits : " . $e->getMessage());
        }
    }

    /**
     * Récupère les produits populaires
     */
    public function obtenirProduitsPopulaires($limite = 6) {
        try {
            $requete = "
                SELECT 
                    p.idProduit,
                    p.nomProduit,
                    p.description,
                    p.prix,
                    p.duree,
                    p.unite,
                    f.nomFamille,
                    f.icone as famille_icone,
                    f.couleur as famille_couleur
                FROM produit p
                LEFT JOIN famille f ON p.idFamille = f.idFamille
                WHERE p.actif = 1 AND p.populaire = 1
                ORDER BY p.position ASC, p.nomProduit ASC
                LIMIT :limite
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des produits populaires : " . $e->getMessage());
        }
    }

    /**
     * Ajoute un nouveau produit
     */
    public function ajouterProduit($donnees) {
        try {
            $requete = "
                INSERT INTO produit 
                (position, nomProduit, description, prix, duree, unite, idFamille, actif, populaire)
                VALUES 
                (:position, :nomProduit, :description, :prix, :duree, :unite, :idFamille, :actif, :populaire)
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':position', $donnees['position'], PDO::PARAM_INT);
            $stmt->bindValue(':nomProduit', $donnees['nomProduit'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $donnees['description'], PDO::PARAM_STR);
            $stmt->bindValue(':prix', $donnees['prix'], PDO::PARAM_STR);
            $stmt->bindValue(':duree', $donnees['duree'], PDO::PARAM_INT);
            $stmt->bindValue(':unite', $donnees['unite'], PDO::PARAM_STR);
            $stmt->bindValue(':idFamille', $donnees['idFamille'], PDO::PARAM_INT);
            $stmt->bindValue(':actif', $donnees['actif'], PDO::PARAM_INT);
            $stmt->bindValue(':populaire', $donnees['populaire'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'ajout du produit : " . $e->getMessage());
        }
    }

    /**
     * Met à jour un produit
     */
    public function mettreAJourProduit($idProduit, $donnees) {
        try {
            $requete = "
                UPDATE produit SET 
                    position = :position,
                    nomProduit = :nomProduit,
                    description = :description,
                    prix = :prix,
                    duree = :duree,
                    unite = :unite,
                    idFamille = :idFamille,
                    actif = :actif,
                    populaire = :populaire
                WHERE idProduit = :idProduit
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idProduit', $idProduit, PDO::PARAM_INT);
            $stmt->bindValue(':position', $donnees['position'], PDO::PARAM_INT);
            $stmt->bindValue(':nomProduit', $donnees['nomProduit'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $donnees['description'], PDO::PARAM_STR);
            $stmt->bindValue(':prix', $donnees['prix'], PDO::PARAM_STR);
            $stmt->bindValue(':duree', $donnees['duree'], PDO::PARAM_INT);
            $stmt->bindValue(':unite', $donnees['unite'], PDO::PARAM_STR);
            $stmt->bindValue(':idFamille', $donnees['idFamille'], PDO::PARAM_INT);
            $stmt->bindValue(':actif', $donnees['actif'], PDO::PARAM_INT);
            $stmt->bindValue(':populaire', $donnees['populaire'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour du produit : " . $e->getMessage());
        }
    }

    /**
     * Supprime un produit
     */
    public function supprimerProduit($idProduit) {
        try {
            $requete = "DELETE FROM produit WHERE idProduit = :idProduit";
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idProduit', $idProduit, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression du produit : " . $e->getMessage());
        }
    }

    /**
     * Active/désactive un produit
     */
    public function toggleActifProduit($idProduit) {
        try {
            $requete = "UPDATE produit SET actif = !actif WHERE idProduit = :idProduit";
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idProduit', $idProduit, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors du changement de statut : " . $e->getMessage());
        }
    }

    /**
     * Récupère toutes les familles
     */
    public function obtenirToutesLesFamilles($actifSeulement = false) {
        try {
            $whereClause = $actifSeulement ? "WHERE actif = 1" : "";
            
            $requete = "
                SELECT 
                    idFamille,
                    nomFamille,
                    description,
                    icone,
                    couleur,
                    actif
                FROM famille 
                $whereClause
                ORDER BY nomFamille ASC
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des familles : " . $e->getMessage());
        }
    }

    /**
     * Obtient les statistiques des produits
     */
    public function obtenirStatistiques() {
        try {
            $requete = "
                SELECT 
                    COUNT(*) as totalProduits,
                    SUM(actif) as produitsActifs,
                    COUNT(*) - SUM(actif) as produitsInactifs,
                    SUM(populaire) as produitsPopulaires,
                    AVG(prix) as prixMoyen,
                    MIN(prix) as prixMin,
                    MAX(prix) as prixMax,
                    AVG(duree) as dureeMoyenne
                FROM produit
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Erreur lors du calcul des statistiques : " . $e->getMessage());
        }
    }

    /**
     * Recherche des produits
     */
    public function rechercherProduits($terme, $actifSeulement = true) {
        try {
            $whereClause = $actifSeulement ? "AND p.actif = 1" : "";
            
            $requete = "
                SELECT 
                    p.idProduit,
                    p.nomProduit,
                    p.description,
                    p.prix,
                    p.duree,
                    p.unite,
                    f.nomFamille,
                    f.icone as famille_icone
                FROM produit p
                LEFT JOIN famille f ON p.idFamille = f.idFamille
                WHERE (p.nomProduit LIKE :terme OR p.description LIKE :terme) $whereClause
                ORDER BY p.position ASC, p.nomProduit ASC
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':terme', '%' . $terme . '%', PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la recherche : " . $e->getMessage());
        }
    }

    /**
     * Réorganise les positions des produits
     */
    public function reorganiserPositions($positions) {
        try {
            $this->connexion->beginTransaction();
            
            $requete = "UPDATE produit SET position = :position WHERE idProduit = :idProduit";
            $stmt = $this->connexion->prepare($requete);
            
            foreach ($positions as $idProduit => $position) {
                $stmt->bindValue(':idProduit', $idProduit, PDO::PARAM_INT);
                $stmt->bindValue(':position', $position, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $this->connexion->commit();
            return true;
        } catch (Exception $e) {
            $this->connexion->rollBack();
            throw new Exception("Erreur lors de la réorganisation : " . $e->getMessage());
        }
    }

    /**
     * Ferme la connexion
     */
    public function __destruct() {
        $this->connexion = null;
    }
}
?>
