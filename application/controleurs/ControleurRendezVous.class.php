<?php
class ControleurRendezVous {
    
    private $gestionRendezVous;
    
    public function __construct() {
        require_once Chemins::MODELES . 'gestion_rendezvous.class.php';
        $this->gestionRendezVous = new gestion_rendezvous();
    }
    
    public function listerRendezVous() {
        // Vérification de connexion admin
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['login_admin'])) {
            header("Location: index.php?controleur=Admin&action=connexion");
            exit();
        }

        $lesRendezVous = $this->gestionRendezVous->obtenirTousLesRendezVous();
        
        // Variables pour la vue
        $titrePage = "Gestion des Rendez-vous";
        $messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
        $messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
        
        // Nettoyer les messages de session
        unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
        
        // Définir la constante pour la sécurité
        if (!defined('INDEX_PRINCIPAL')) {
            define('INDEX_PRINCIPAL', true);
        }
        
        // Utiliser le layout admin commun
        $pageActive = 'rendez-vous';
        $titrePage = "Gestion des Rendez-vous";
        
        // Capturer le contenu de la vue
        ob_start();
        include_once("application/vues/partie_admin/v_gestion_rendezvous.inc.php");
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }
    
    public function afficherFormulairePriseRendezVous() {
        // Rediriger vers la page d'accueil avec l'ancre vers la section rendez-vous
        header("Location: index.php#rendez-vous");
        exit();
    }
    
    public function traiterPriseRendezVous() {
        if (isset($_POST['submit_rdv'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $telephone = htmlspecialchars($_POST['telephone']);
            $email = htmlspecialchars($_POST['email']);
            $date_rdv = $_POST['date_rdv'];
            $heure_rdv = $_POST['heure_rdv'];
            $service_id = (int)$_POST['service_id'];
            $message = htmlspecialchars($_POST['message']);
            
            // Vérifier les données requises
            if (!empty($nom) && !empty($prenom) && !empty($telephone) && !empty($date_rdv) && !empty($heure_rdv) && $service_id > 0) {
                $resultat = $this->gestionRendezVous->ajouterRendezVous(
                    $nom, $prenom, $telephone, $email, 
                    $date_rdv, $heure_rdv, $service_id, $message
                );
                
                if ($resultat) {
                    $_SESSION['message'] = "Votre demande de rendez-vous a été envoyée avec succès. Nous vous contacterons rapidement pour confirmer.";
                    $_SESSION['type_message'] = "success";
                } else {
                    $_SESSION['message'] = "Une erreur est survenue. Veuillez réessayer.";
                    $_SESSION['type_message'] = "error";
                }
            } else {
                $_SESSION['message'] = "Veuillez remplir tous les champs obligatoires.";
                $_SESSION['type_message'] = "error";
            }
            
            header("Location: index.php#rendez-vous");
            exit();
        }
    }
    
    public function confirmerRendezVous() {
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
            $this->repondreJSON(['succes' => false, 'message' => 'ID du rendez-vous requis']);
            return;
        }

        try {
            $resultat = $this->gestionRendezVous->confirmerRendezVous($id);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Rendez-vous confirmé avec succès']);
            } else {
                $this->repondreJSON(['succes' => false, 'message' => 'Erreur lors de la confirmation']);
            }
        } catch (Exception $e) {
            $this->repondreJSON(['succes' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }
    
    public function supprimerRendezVous() {
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
            $this->repondreJSON(['succes' => false, 'message' => 'ID du rendez-vous requis']);
            return;
        }

        try {
            $resultat = $this->gestionRendezVous->supprimerRendezVous($id);
            
            if ($resultat) {
                $this->repondreJSON(['succes' => true, 'message' => 'Rendez-vous supprimé avec succès']);
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
    
    public function obtenirCreneauxDisponibles() {
        // Activer les erreurs pour debug
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        try {
            $date = $_GET['date'] ?? date('Y-m-d');
            
            // Log pour debug
            error_log("DEBUG RDV: Date reçue = " . $date);
            
            $creneaux = $this->gestionRendezVous->obtenirCreneauxDisponibles($date);
            
            // Log pour debug
            error_log("DEBUG RDV: Créneaux obtenus = " . print_r($creneaux, true));
            
            header('Content-Type: application/json');
            echo json_encode($creneaux);
            exit();
            
        } catch (Exception $e) {
            error_log("ERREUR RDV: " . $e->getMessage());
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit();
        }
    }
}
?>
