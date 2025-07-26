<?php

/**
 * Contrôleur de gestion de la galerie
 * @author TR Hair Design
 * @version 2.0
 */
class ControleurGalerie {

    public function __construct() {
        // Chargement des dépendances
        if (file_exists(Chemins::MODELES . 'gestion_galerie.class.php')) {
            require_once Chemins::MODELES . 'gestion_galerie.class.php';
        }
    }

    /**
     * Affiche la page de gestion de la galerie
     */
    public function afficherGestionGalerie() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        try {
            // Récupération des images
            $lesImages = GestionGalerie::getLesImages();
            $statistiques = GestionGalerie::getStatistiquesGalerie();
            
            // Variables pour le layout admin
            $pageActive = 'galerie';
            $titrePage = 'Gestion de la Galerie - Administration';
            
            // Capturer le contenu de la vue
            ob_start();
            include_once("application/vues/partie_admin/v_gestion_galerie.inc.php");
            $contenu = ob_get_clean();
            
            // Charger le layout avec le contenu
            require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
            
        } catch (Exception $e) {
            $_SESSION['message_erreur'] = "Erreur lors du chargement de la galerie : " . $e->getMessage();
            header("Location: index.php?controleur=Admin&action=afficherIndex");
            exit();
        }
    }

    /**
     * Affiche le formulaire d'ajout d'image
     */
    public function afficherAjoutImage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        // Variables pour le layout admin
        $pageActive = 'galerie';
        $titrePage = 'Ajouter une Image - Administration';
        
        // Capturer le contenu de la vue
        ob_start();
        include_once("application/vues/partie_admin/v_ajout_image.inc.php");
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }

    /**
     * Traite l'ajout d'une nouvelle image
     */
    public function ajouterImage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controleur=Galerie&action=afficherAjoutImage");
            exit();
        }

        try {
            // Validation des données
            $nomImage = trim($_POST['nomImage'] ?? '');
            $titreImage = trim($_POST['titreImage'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ordre = (int)($_POST['ordre'] ?? 0);
            $actif = isset($_POST['actif']) ? 1 : 0;

            if (empty($nomImage)) {
                throw new Exception("Le nom de l'image est obligatoire");
            }

            if (empty($titreImage)) {
                throw new Exception("Le titre de l'image est obligatoire");
            }

            // Traitement de l'upload
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Erreur lors de l'upload de l'image");
            }

            $uploadResult = GestionGalerie::traiterUploadImage($_FILES['image']);
            
            // Ajout en base de données
            $idImage = GestionGalerie::ajouterImage(
                $nomImage,
                $titreImage,
                $description,
                $uploadResult['cheminComplet'],
                $uploadResult['taille'],
                $uploadResult['type'],
                $ordre
            );

            if ($idImage) {
                // Mise à jour du statut si nécessaire
                if (!$actif) {
                    GestionGalerie::toggleStatutImage($idImage);
                }
                
                $_SESSION['message_succes'] = "Image ajoutée avec succès !";
                header("Location: index.php?controleur=Galerie&action=afficherGestionGalerie");
                exit();
            } else {
                throw new Exception("Erreur lors de l'ajout en base de données");
            }

        } catch (Exception $e) {
            $_SESSION['message_erreur'] = "Erreur lors de l'ajout de l'image : " . $e->getMessage();
            header("Location: index.php?controleur=Galerie&action=afficherAjoutImage");
            exit();
        }
    }

    /**
     * Affiche le formulaire de modification d'image
     */
    public function afficherModifierImage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        $idImage = (int)($_GET['id'] ?? 0);
        if (!$idImage) {
            $_SESSION['message_erreur'] = "ID d'image invalide";
            header("Location: index.php?controleur=Galerie&action=afficherGestionGalerie");
            exit();
        }

        try {
            $image = GestionGalerie::getImageById($idImage);
            if (!$image) {
                throw new Exception("Image non trouvée");
            }

            // Variables pour le layout admin
            $pageActive = 'galerie';
            $titrePage = 'Modifier une Image - Administration';
            
            // Capturer le contenu de la vue
            ob_start();
            include_once("application/vues/partie_admin/v_modifier_image.inc.php");
            $contenu = ob_get_clean();
            
            // Charger le layout avec le contenu
            require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
            
        } catch (Exception $e) {
            $_SESSION['message_erreur'] = "Erreur : " . $e->getMessage();
            header("Location: index.php?controleur=Galerie&action=afficherGestionGalerie");
            exit();
        }
    }

    /**
     * Traite la modification d'une image
     */
    public function modifierImage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controleur=Galerie&action=afficherGestionGalerie");
            exit();
        }

        try {
            $idImage = (int)($_POST['idImage'] ?? 0);
            if (!$idImage) {
                throw new Exception("ID d'image invalide");
            }

            // Validation des données
            $nomImage = trim($_POST['nomImage'] ?? '');
            $titreImage = trim($_POST['titreImage'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $ordre = (int)($_POST['ordre'] ?? 0);
            $actif = isset($_POST['actif']) ? 1 : 0;

            if (empty($nomImage)) {
                throw new Exception("Le nom de l'image est obligatoire");
            }

            if (empty($titreImage)) {
                throw new Exception("Le titre de l'image est obligatoire");
            }

            // Traitement de l'upload si une nouvelle image est fournie
            $cheminImage = null;
            $taille = null;
            $type = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = GestionGalerie::traiterUploadImage($_FILES['image']);
                $cheminImage = $uploadResult['cheminComplet'];
                $taille = $uploadResult['taille'];
                $type = $uploadResult['type'];
            }

            // Modification en base de données
            $resultat = GestionGalerie::modifierImage(
                $idImage,
                $nomImage,
                $titreImage,
                $description,
                $cheminImage,
                $taille,
                $type,
                $actif,
                $ordre
            );

            if ($resultat) {
                $_SESSION['message_succes'] = "Image modifiée avec succès !";
            } else {
                throw new Exception("Erreur lors de la modification");
            }

            header("Location: index.php?controleur=Galerie&action=afficherGestionGalerie");
            exit();

        } catch (Exception $e) {
            $_SESSION['message_erreur'] = "Erreur lors de la modification : " . $e->getMessage();
            header("Location: index.php?controleur=Galerie&action=afficherModifierImage&id=" . $idImage);
            exit();
        }
    }

    /**
     * Supprime une image
     */
    public function supprimerImage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        $idImage = (int)($_GET['id'] ?? 0);
        if (!$idImage) {
            $_SESSION['message_erreur'] = "ID d'image invalide";
            header("Location: index.php?controleur=Galerie&action=afficherGestionGalerie");
            exit();
        }

        try {
            $resultat = GestionGalerie::supprimerImage($idImage);
            
            if ($resultat) {
                $_SESSION['message_succes'] = "Image supprimée avec succès !";
            } else {
                throw new Exception("Erreur lors de la suppression");
            }

        } catch (Exception $e) {
            $_SESSION['message_erreur'] = "Erreur lors de la suppression : " . $e->getMessage();
        }

        header("Location: index.php?controleur=Galerie&action=afficherGestionGalerie");
        exit();
    }

    /**
     * Change le statut d'une image (actif/inactif)
     */
    public function toggleStatutImage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        $idImage = (int)($_GET['id'] ?? 0);
        if (!$idImage) {
            $_SESSION['message_erreur'] = "ID d'image invalide";
            header("Location: index.php?controleur=Galerie&action=afficherGestionGalerie");
            exit();
        }

        try {
            $resultat = GestionGalerie::toggleStatutImage($idImage);
            
            if ($resultat) {
                $_SESSION['message_succes'] = "Statut de l'image modifié avec succès !";
            } else {
                throw new Exception("Erreur lors du changement de statut");
            }

        } catch (Exception $e) {
            $_SESSION['message_erreur'] = "Erreur : " . $e->getMessage();
        }

        header("Location: index.php?controleur=Galerie&action=afficherGestionGalerie");
        exit();
    }

    /**
     * Met à jour l'ordre des images (AJAX)
     */
    public function updateOrdreImages() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit();
        }

        try {
            $ordreImages = $_POST['ordre'] ?? [];
            if (empty($ordreImages)) {
                throw new Exception("Aucun ordre fourni");
            }

            $resultat = GestionGalerie::updateOrdreImages($ordreImages);
            
            if ($resultat) {
                echo json_encode(['success' => true, 'message' => 'Ordre mis à jour avec succès']);
            } else {
                throw new Exception("Erreur lors de la mise à jour de l'ordre");
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Affiche la galerie publique
     */
    public function afficherGalerie() {
        try {
            $lesImages = GestionGalerie::getImagesActives();
            
            // Variables pour la vue
            $titrePage = 'Galerie - TR Hair Design';
            
            // Capturer le contenu de la vue
            ob_start();
            include_once("application/vues/v_galerie.inc.php");
            $contenu = ob_get_clean();
            
            // Charger le layout principal
            require_once Chemins::VUES . 'permanentes/v_entete.inc.php';
            echo $contenu;
            require_once Chemins::VUES . 'permanentes/v_pied.inc.php';
            
        } catch (Exception $e) {
            // En cas d'erreur, afficher une galerie vide
            $lesImages = [];
            $titrePage = 'Galerie - TR Hair Design';
            
            ob_start();
            include_once("application/vues/v_galerie.inc.php");
            $contenu = ob_get_clean();
            
            require_once Chemins::VUES . 'permanentes/v_entete.inc.php';
            echo $contenu;
            require_once Chemins::VUES . 'permanentes/v_pied.inc.php';
        }
    }
}

?> 