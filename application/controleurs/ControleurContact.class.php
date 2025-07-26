<?php
class ControleurContact {
    
    private $gestionContact;
    
    public function __construct() {
        require_once Chemins::MODELES . 'gestion_contact.class.php';
        $this->gestionContact = new gestion_contact();
    }
    
    public function afficherFormulaire() {
        include_once("application/vues/v_contact.inc.php");
    }
    
    public function traiterContact() {
        if (isset($_POST['submit_contact'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $telephone = htmlspecialchars($_POST['telephone']);
            $sujet = htmlspecialchars($_POST['sujet']);
            $message = htmlspecialchars($_POST['message']);
            
            // Vérifier les données requises
            if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($sujet) && !empty($message)) {
                $resultat = $this->gestionContact->ajouterMessage(
                    $nom, $prenom, $email, $telephone, $sujet, $message
                );
                
                if ($resultat) {
                    $_SESSION['message'] = "Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.";
                    $_SESSION['type_message'] = "success";
                } else {
                    $_SESSION['message'] = "Erreur lors de l'envoi du message. Veuillez réessayer.";
                    $_SESSION['type_message'] = "error";
                }
            } else {
                $_SESSION['message'] = "Veuillez remplir tous les champs obligatoires.";
                $_SESSION['type_message'] = "error";
            }
            
            header("Location: index.php?controleur=Contact&action=afficherFormulaire");
            exit();
        }
    }
    
    public function listerMessages() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }
        
        $gestionContact = new gestion_contact();
        $lesMessages = $gestionContact->obtenirTousLesMessages();
        $stats = $gestionContact->obtenirStatistiquesMessages();
        
        // Variables pour la vue
        $titrePage = "Gestion des Messages de Contact";
        $messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
        $messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
        
        // Nettoyer les messages de session
        unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
        
        // Définir la constante pour la sécurité
        if (!defined('INDEX_PRINCIPAL')) {
            define('INDEX_PRINCIPAL', true);
        }
        
        // Utiliser le layout admin commun
        $pageActive = 'messages';
        
        // Capturer le contenu de la vue
        ob_start();
        include_once("application/vues/partie_admin/v_gestion_contact.inc.php");
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }
    
    public function marquerLu() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $this->repondreJSON(['succes' => false, 'message' => 'ID du message requis']);
            return;
        }

        try {
            $resultat = $this->gestionContact->marquerCommeLu($id);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Message marqué comme lu']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors du marquage']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }
    
    public function supprimerMessage() {
        if (!$this->estConnecte()) {
            $this->repondreJSON(['succes' => false, 'message' => 'Non autorisé']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->repondreJSON(['succes' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            $this->repondreJSON(['succes' => false, 'message' => 'ID du message requis']);
            return;
        }

        try {
            $resultat = $this->gestionContact->supprimerMessage($id);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Message supprimé avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de la suppression']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    private function estConnecte(): bool {
        return isset($_SESSION['login_admin']) && !empty($_SESSION['login_admin']);
    }

    private function repondreJSON($data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    public function voirMessage() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $message = $this->gestionContact->obtenirMessageParId($id);
            
            if ($message) {
                // Marquer comme lu automatiquement
                $this->gestionContact->marquerCommeLu($id);
                
                // Variables pour la vue
                $titrePage = "Détail du Message";
                $messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
                $messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
                
                // Nettoyer les messages de session
                unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
                
                // Définir la constante pour la sécurité
                if (!defined('INDEX_PRINCIPAL')) {
                    define('INDEX_PRINCIPAL', true);
                }
                
                // Utiliser le layout admin commun
                $pageActive = 'messages';
                
                // Capturer le contenu de la vue
                ob_start();
                include_once("application/vues/partie_admin/v_detail_message.inc.php");
                $contenu = ob_get_clean();
                
                // Charger le layout avec le contenu
                require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
            } else {
                $_SESSION['message_erreur'] = "Message introuvable.";
                header("Location: index.php?controleur=Contact&action=listerMessages");
                exit();
            }
        } else {
            header("Location: index.php?controleur=Contact&action=listerMessages");
            exit();
        }
    }

    public function voirDetail() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $message = $this->gestionContact->obtenirMessageParId($id);
            
            if ($message) {
                // Marquer comme lu automatiquement
                $this->gestionContact->marquerCommeLu($id);
                include_once("application/vues/partie_admin/v_detail_message.inc.php");
            } else {
                $_SESSION['message_admin'] = "Message introuvable.";
                $_SESSION['type_message_admin'] = "error";
                header("Location: index.php?controleur=Contact&action=listerMessages");
                exit();
            }
        } else {
            header("Location: index.php?controleur=Contact&action=listerMessages");
            exit();
        }
    }
}
?>
