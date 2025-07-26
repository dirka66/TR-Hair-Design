<?php

/**
 * Gestion de la galerie d'images
 * @author TR Hair Design
 * @version 2.0
 */
class GestionGalerie {

    private static $pdoCnxBase;
    private static $pdoStResults;
    private static $requete;
    private static $resultat;

    /**
     * Établit la connexion à la base de données
     */
    public static function seConnecter() {
        if (self::$pdoCnxBase == null) {
            try {
                self::$pdoCnxBase = new PDO(
                    "mysql:host=" . MysqlConfig::SERVEUR . ";dbname=" . MysqlConfig::BASE . ";charset=utf8mb4",
                    MysqlConfig::UTILISATEUR,
                    MysqlConfig::MOT_DE_PASSE,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                error_log("Erreur connexion galerie: " . $e->getMessage());
                throw new Exception("Erreur de connexion à la base de données");
            }
        }
    }

    /**
     * Récupère toutes les images de la galerie
     */
    public static function getLesImages() {
        self::seConnecter();

        self::$requete = "
        SELECT 
            idImage,
            nomImage,
            titreImage,
            description,
            cheminImage,
            taille,
            type,
            actif,
            ordre,
            dateAjout
        FROM galerie
        ORDER BY ordre ASC, dateAjout DESC
        ";
        
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();
        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    /**
     * Récupère les images actives pour l'affichage public
     */
    public static function getImagesActives() {
        self::seConnecter();

        self::$requete = "
        SELECT 
            idImage,
            nomImage,
            titreImage,
            description,
            cheminImage,
            taille,
            type,
            ordre,
            dateAjout
        FROM galerie
        WHERE actif = 1
        ORDER BY ordre ASC, dateAjout DESC
        ";
        
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetchAll();
        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    /**
     * Récupère une image par son ID
     */
    public static function getImageById($idImage) {
        self::seConnecter();

        self::$requete = "
        SELECT 
            idImage,
            nomImage,
            titreImage,
            description,
            cheminImage,
            taille,
            type,
            actif,
            ordre,
            dateAjout
        FROM galerie
        WHERE idImage = :idImage
        ";
        
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->bindParam(':idImage', $idImage, PDO::PARAM_INT);
        self::$pdoStResults->execute();
        self::$resultat = self::$pdoStResults->fetch();
        self::$pdoStResults->closeCursor();

        return self::$resultat;
    }

    /**
     * Ajoute une nouvelle image à la galerie
     */
    public static function ajouterImage($nomImage, $titreImage, $description, $cheminImage, $taille, $type, $ordre = 0) {
        self::seConnecter();

        try {
            self::$requete = "
            INSERT INTO galerie (nomImage, titreImage, description, cheminImage, taille, type, ordre, dateAjout)
            VALUES (:nomImage, :titreImage, :description, :cheminImage, :taille, :type, :ordre, NOW())
            ";
            
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindParam(':nomImage', $nomImage, PDO::PARAM_STR);
            self::$pdoStResults->bindParam(':titreImage', $titreImage, PDO::PARAM_STR);
            self::$pdoStResults->bindParam(':description', $description, PDO::PARAM_STR);
            self::$pdoStResults->bindParam(':cheminImage', $cheminImage, PDO::PARAM_STR);
            self::$pdoStResults->bindParam(':taille', $taille, PDO::PARAM_INT);
            self::$pdoStResults->bindParam(':type', $type, PDO::PARAM_STR);
            self::$pdoStResults->bindParam(':ordre', $ordre, PDO::PARAM_INT);
            
            $resultat = self::$pdoStResults->execute();
            self::$pdoStResults->closeCursor();
            
            return $resultat ? self::$pdoCnxBase->lastInsertId() : false;
            
        } catch (PDOException $e) {
            error_log("Erreur ajout image: " . $e->getMessage());
            throw new Exception("Erreur lors de l'ajout de l'image");
        }
    }

    /**
     * Modifie une image existante
     */
    public static function modifierImage($idImage, $nomImage, $titreImage, $description, $cheminImage = null, $taille = null, $type = null, $actif = 1, $ordre = 0) {
        self::seConnecter();

        try {
            $requete = "
            UPDATE galerie 
            SET nomImage = :nomImage,
                titreImage = :titreImage,
                description = :description,
                actif = :actif,
                ordre = :ordre
            ";
            
            $params = [
                ':idImage' => $idImage,
                ':nomImage' => $nomImage,
                ':titreImage' => $titreImage,
                ':description' => $description,
                ':actif' => $actif,
                ':ordre' => $ordre
            ];
            
            // Ajouter les champs optionnels si fournis
            if ($cheminImage !== null) {
                $requete .= ", cheminImage = :cheminImage";
                $params[':cheminImage'] = $cheminImage;
            }
            if ($taille !== null) {
                $requete .= ", taille = :taille";
                $params[':taille'] = $taille;
            }
            if ($type !== null) {
                $requete .= ", type = :type";
                $params[':type'] = $type;
            }
            
            $requete .= " WHERE idImage = :idImage";
            
            self::$pdoStResults = self::$pdoCnxBase->prepare($requete);
            $resultat = self::$pdoStResults->execute($params);
            self::$pdoStResults->closeCursor();
            
            return $resultat;
            
        } catch (PDOException $e) {
            error_log("Erreur modification image: " . $e->getMessage());
            throw new Exception("Erreur lors de la modification de l'image");
        }
    }

    /**
     * Supprime une image de la galerie
     */
    public static function supprimerImage($idImage) {
        self::seConnecter();

        try {
            // Récupérer le chemin de l'image avant suppression
            $image = self::getImageById($idImage);
            if (!$image) {
                return false;
            }

            self::$requete = "DELETE FROM galerie WHERE idImage = :idImage";
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindParam(':idImage', $idImage, PDO::PARAM_INT);
            $resultat = self::$pdoStResults->execute();
            self::$pdoStResults->closeCursor();

            // Supprimer le fichier physique si la suppression en base a réussi
            if ($resultat && file_exists($image->cheminImage)) {
                unlink($image->cheminImage);
            }

            return $resultat;
            
        } catch (PDOException $e) {
            error_log("Erreur suppression image: " . $e->getMessage());
            throw new Exception("Erreur lors de la suppression de l'image");
        }
    }

    /**
     * Change le statut actif/inactif d'une image
     */
    public static function toggleStatutImage($idImage) {
        self::seConnecter();

        try {
            self::$requete = "
            UPDATE galerie 
            SET actif = CASE WHEN actif = 1 THEN 0 ELSE 1 END
            WHERE idImage = :idImage
            ";
            
            self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
            self::$pdoStResults->bindParam(':idImage', $idImage, PDO::PARAM_INT);
            $resultat = self::$pdoStResults->execute();
            self::$pdoStResults->closeCursor();
            
            return $resultat;
            
        } catch (PDOException $e) {
            error_log("Erreur toggle statut image: " . $e->getMessage());
            throw new Exception("Erreur lors du changement de statut");
        }
    }

    /**
     * Met à jour l'ordre des images
     */
    public static function updateOrdreImages($ordreImages) {
        self::seConnecter();

        try {
            self::$pdoCnxBase->beginTransaction();
            
            foreach ($ordreImages as $idImage => $ordre) {
                self::$requete = "UPDATE galerie SET ordre = :ordre WHERE idImage = :idImage";
                self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
                self::$pdoStResults->bindParam(':ordre', $ordre, PDO::PARAM_INT);
                self::$pdoStResults->bindParam(':idImage', $idImage, PDO::PARAM_INT);
                self::$pdoStResults->execute();
            }
            
            self::$pdoCnxBase->commit();
            return true;
            
        } catch (PDOException $e) {
            self::$pdoCnxBase->rollBack();
            error_log("Erreur update ordre images: " . $e->getMessage());
            throw new Exception("Erreur lors de la mise à jour de l'ordre");
        }
    }

    /**
     * Récupère les statistiques de la galerie
     */
    public static function getStatistiquesGalerie() {
        self::seConnecter();
        
        $stats = new stdClass();
        
        // Total des images
        self::$requete = "SELECT COUNT(*) as total FROM galerie";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        $resultat = self::$pdoStResults->fetch();
        $stats->totalImages = $resultat->total;
        
        // Images actives
        self::$requete = "SELECT COUNT(*) as actives FROM galerie WHERE actif = 1";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        $resultat = self::$pdoStResults->fetch();
        $stats->imagesActives = $resultat->actives;
        
        // Images inactives
        self::$requete = "SELECT COUNT(*) as inactives FROM galerie WHERE actif = 0";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        $resultat = self::$pdoStResults->fetch();
        $stats->imagesInactives = $resultat->inactives;
        
        // Taille totale des images
        self::$requete = "SELECT SUM(taille) as tailleTotale FROM galerie";
        self::$pdoStResults = self::$pdoCnxBase->prepare(self::$requete);
        self::$pdoStResults->execute();
        $resultat = self::$pdoStResults->fetch();
        $stats->tailleTotale = $resultat->tailleTotale ?? 0;
        
        self::$pdoStResults->closeCursor();
        
        return $stats;
    }

    /**
     * Valide et traite l'upload d'une image
     */
    public static function traiterUploadImage($file, $dossierDestination = 'public/uploads/images/') {
        // Vérifications de sécurité
        $typesAutorises = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $tailleMax = 5 * 1024 * 1024; // 5MB
        
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception("Aucun fichier uploadé ou erreur d'upload");
        }
        
        if (!in_array($file['type'], $typesAutorises)) {
            throw new Exception("Type de fichier non autorisé. Types acceptés : JPG, PNG, GIF, WEBP");
        }
        
        if ($file['size'] > $tailleMax) {
            throw new Exception("Fichier trop volumineux. Taille maximum : 5MB");
        }
        
        // Créer le dossier de destination s'il n'existe pas
        if (!is_dir($dossierDestination)) {
            mkdir($dossierDestination, 0755, true);
        }
        
        // Générer un nom de fichier unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nomFichier = 'galerie_' . uniqid() . '.' . $extension;
        $cheminComplet = $dossierDestination . $nomFichier;
        
        // Déplacer le fichier
        if (!move_uploaded_file($file['tmp_name'], $cheminComplet)) {
            throw new Exception("Erreur lors du déplacement du fichier");
        }
        
        return [
            'nomFichier' => $nomFichier,
            'cheminComplet' => $cheminComplet,
            'taille' => $file['size'],
            'type' => $file['type']
        ];
    }

    /**
     * Déconnecte de la base de données
     */
    public static function seDeconnecter() {
        self::$pdoCnxBase = null;
        self::$pdoStResults = null;
        self::$resultat = null;
    }
}

?> 