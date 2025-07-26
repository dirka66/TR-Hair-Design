<?php

/**
 * Modèle moderne pour la gestion des familles (catégories de services)
 * @author TR Hair Design
 * @version 2.0
 */
class GestionFamillesModerne {
    
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
     * Récupère toutes les familles
     */
    public function obtenirToutesLesFamilles($actifSeulement = false) {
        try {
            $whereClause = $actifSeulement ? "WHERE f.actif = 1" : "";
            
            $requete = "
                SELECT 
                    f.idFamille,
                    f.nomFamille,
                    f.description,
                    f.icone,
                    f.couleur,
                    f.actif,
                    COUNT(p.idProduit) as nombreProduits,
                    COUNT(CASE WHEN p.actif = 1 THEN 1 END) as produitsActifs
                FROM famille f
                LEFT JOIN produit p ON f.idFamille = p.idFamille
                $whereClause
                GROUP BY f.idFamille
                ORDER BY f.nomFamille ASC
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des familles : " . $e->getMessage());
        }
    }

    /**
     * Récupère une famille par son ID
     */
    public function obtenirFamilleParId($idFamille) {
        try {
            $requete = "
                SELECT 
                    f.idFamille,
                    f.nomFamille,
                    f.description,
                    f.icone,
                    f.couleur,
                    f.actif,
                    COUNT(p.idProduit) as nombreProduits
                FROM famille f
                LEFT JOIN produit p ON f.idFamille = p.idFamille
                WHERE f.idFamille = :idFamille
                GROUP BY f.idFamille
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idFamille', $idFamille, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération de la famille : " . $e->getMessage());
        }
    }

    /**
     * Ajoute une nouvelle famille
     */
    public function ajouterFamille($donnees) {
        try {
            $requete = "
                INSERT INTO famille 
                (nomFamille, description, icone, couleur, actif)
                VALUES 
                (:nomFamille, :description, :icone, :couleur, :actif)
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':nomFamille', $donnees['nomFamille'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $donnees['description'], PDO::PARAM_STR);
            $stmt->bindValue(':icone', $donnees['icone'], PDO::PARAM_STR);
            $stmt->bindValue(':couleur', $donnees['couleur'], PDO::PARAM_STR);
            $stmt->bindValue(':actif', $donnees['actif'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de l'ajout de la famille : " . $e->getMessage());
        }
    }

    /**
     * Met à jour une famille
     */
    public function mettreAJourFamille($idFamille, $donnees) {
        try {
            $requete = "
                UPDATE famille SET 
                    nomFamille = :nomFamille,
                    description = :description,
                    icone = :icone,
                    couleur = :couleur,
                    actif = :actif
                WHERE idFamille = :idFamille
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idFamille', $idFamille, PDO::PARAM_INT);
            $stmt->bindValue(':nomFamille', $donnees['nomFamille'], PDO::PARAM_STR);
            $stmt->bindValue(':description', $donnees['description'], PDO::PARAM_STR);
            $stmt->bindValue(':icone', $donnees['icone'], PDO::PARAM_STR);
            $stmt->bindValue(':couleur', $donnees['couleur'], PDO::PARAM_STR);
            $stmt->bindValue(':actif', $donnees['actif'], PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour de la famille : " . $e->getMessage());
        }
    }

    /**
     * Supprime une famille
     */
    public function supprimerFamille($idFamille) {
        try {
            $requete = "DELETE FROM famille WHERE idFamille = :idFamille";
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idFamille', $idFamille, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression de la famille : " . $e->getMessage());
        }
    }

    /**
     * Active/désactive une famille
     */
    public function toggleActifFamille($idFamille) {
        try {
            $requete = "UPDATE famille SET actif = !actif WHERE idFamille = :idFamille";
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idFamille', $idFamille, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Erreur lors du changement de statut : " . $e->getMessage());
        }
    }

    /**
     * Compte le nombre de produits dans une famille
     */
    public function compterProduitsParFamille($idFamille) {
        try {
            $requete = "SELECT COUNT(*) as nombre FROM produit WHERE idFamille = :idFamille";
            $stmt = $this->connexion->prepare($requete);
            $stmt->bindValue(':idFamille', $idFamille, PDO::PARAM_INT);
            $stmt->execute();
            $resultat = $stmt->fetch();
            return $resultat->nombre ?? 0;
        } catch (Exception $e) {
            throw new Exception("Erreur lors du comptage : " . $e->getMessage());
        }
    }

    /**
     * Récupère les familles avec leurs produits
     */
    public function obtenirFamillesAvecProduits($actifSeulement = true) {
        try {
            $whereClause = $actifSeulement ? "WHERE f.actif = 1 AND p.actif = 1" : "";
            
            $requete = "
                SELECT 
                    f.idFamille,
                    f.nomFamille,
                    f.description,
                    f.icone,
                    f.couleur,
                    p.idProduit,
                    p.nomProduit,
                    p.prix,
                    p.duree,
                    p.unite
                FROM famille f
                LEFT JOIN produit p ON f.idFamille = p.idFamille
                $whereClause
                ORDER BY f.nomFamille ASC, p.position ASC, p.nomProduit ASC
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }

    /**
     * Obtient les statistiques des familles
     */
    public function obtenirStatistiques() {
        try {
            $requete = "
                SELECT 
                    COUNT(*) as totalFamilles,
                    SUM(actif) as famillesActives,
                    COUNT(*) - SUM(actif) as famillesInactives,
                    (SELECT COUNT(*) FROM produit) as totalProduits,
                    (SELECT AVG(nombreProduits) FROM (
                        SELECT COUNT(p.idProduit) as nombreProduits 
                        FROM famille f 
                        LEFT JOIN produit p ON f.idFamille = p.idFamille 
                        GROUP BY f.idFamille
                    ) as stats) as produitsMoyensParFamille
                FROM famille
            ";
            
            $stmt = $this->connexion->prepare($requete);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Erreur lors du calcul des statistiques : " . $e->getMessage());
        }
    }

    /**
     * Recherche des familles
     */
    public function rechercherFamilles($terme, $actifSeulement = true) {
        try {
            $whereClause = $actifSeulement ? "AND f.actif = 1" : "";
            
            $requete = "
                SELECT 
                    f.idFamille,
                    f.nomFamille,
                    f.description,
                    f.icone,
                    f.couleur,
                    COUNT(p.idProduit) as nombreProduits
                FROM famille f
                LEFT JOIN produit p ON f.idFamille = p.idFamille
                WHERE (f.nomFamille LIKE :terme OR f.description LIKE :terme) $whereClause
                GROUP BY f.idFamille
                ORDER BY f.nomFamille ASC
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
     * Vérifie si le nom de famille existe déjà
     */
    public function nomFamilleExiste($nomFamille, $idFamilleExclure = null) {
        try {
            $requete = "SELECT COUNT(*) as nombre FROM famille WHERE nomFamille = :nomFamille";
            $params = [':nomFamille' => $nomFamille];
            
            if ($idFamilleExclure) {
                $requete .= " AND idFamille != :idFamilleExclure";
                $params[':idFamilleExclure'] = $idFamilleExclure;
            }
            
            $stmt = $this->connexion->prepare($requete);
            foreach ($params as $param => $valeur) {
                $stmt->bindValue($param, $valeur);
            }
            $stmt->execute();
            $resultat = $stmt->fetch();
            return $resultat->nombre > 0;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la vérification : " . $e->getMessage());
        }
    }

    /**
     * Obtient les icônes disponibles
     */
    public function obtenirIconesDisponibles() {
        return [
            'fas fa-cut' => 'Ciseaux',
            'fas fa-palette' => 'Palette',
            'fas fa-spa' => 'Spa',
            'fas fa-crown' => 'Couronne',
            'fas fa-star' => 'Étoile',
            'fas fa-heart' => 'Cœur',
            'fas fa-magic' => 'Magie',
            'fas fa-gem' => 'Diamant',
            'fas fa-female' => 'Femme',
            'fas fa-male' => 'Homme',
            'fas fa-brush' => 'Pinceau',
            'fas fa-tint' => 'Goutte',
            'fas fa-spray-can' => 'Spray',
            'fas fa-hand-sparkles' => 'Mains brillantes'
        ];
    }

    /**
     * Ferme la connexion
     */
    public function __destruct() {
        $this->connexion = null;
    }
}
?>
