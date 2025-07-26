<?php

/**
 * Contrôleur Admin simplifié - Version fonctionnelle
 * @author Tahir Hair Design
 * @version 2.0
 */
class ControleurAdmin {

    public function __construct() {
        // Chargement des dépendances seulement si les fichiers existent
        if (file_exists(Chemins::MODELES . 'gestion_admin.class.php')) {
            require_once Chemins::MODELES . 'gestion_admin.class.php';
        }
        if (file_exists(Chemins::MODELES . 'gestion_horaires.class.php')) {
            require_once Chemins::MODELES . 'gestion_horaires.class.php';
        }
    }

    /**
     * Affiche la page d'accueil de l'administration
     */
    public function afficherIndex() {
        if ($this->estConnecte()) {
            // Récupération des vraies données depuis la base de données
            $statistiques = $this->recupererStatistiques();
            
            // Interface d'administration avec les vraies données
            $this->chargerVueAdminAvecLayout('v_index_admin.inc.php', 'dashboard', $statistiques);
        } else {
            // Formulaire de connexion
            $this->chargerVueAdmin('v_connexion.inc.php');
        }
    }

    /**
     * Alias pour afficherIndex (compatibilité)
     */
    public function index() {
        $this->afficherIndex();
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function connexion() {
        $this->chargerVueAdmin('v_connexion.inc.php');
    }

    /**
     * Alias pour verifierConnexion (compatibilité)
     */
    public function seConnecter() {
        $this->connexion();
    }

    /**
     * Vérifie les identifiants et connecte l'administrateur
     */
    public function verifierConnexion() {
        // Validation des données POST
        $login = $this->filtrerEntree($_POST['login'] ?? '');
        $motDePasse = $_POST['passe'] ?? '';

        if (empty($login) || empty($motDePasse)) {
            $this->afficherErreur("Veuillez saisir vos identifiants.");
            return;
        }

        // Vérification simple (à remplacer par votre logique)
        if ($login === 'admin' && $motDePasse === 'admin123') {
            $this->connecterUtilisateur($login);
            
            // Gestion de la connexion automatique
            if (isset($_POST['connexion_auto'])) {
                $this->definirCookieConnexion($login);
            }
            
            // Redirection vers l'interface admin
            header("Location: index.php?controleur=Admin&action=afficherIndex");
            exit;
        } else {
            $this->chargerVueAdmin('v_acces_interdit.inc.php');
        }
    }

    /**
     * Déconnecte l'administrateur
     */
    public function seDeconnecter() {
        // Suppression de la session
        if (isset($_SESSION['login_admin'])) {
            unset($_SESSION['login_admin']);
        }
        
        // Suppression des autres variables de session admin
        $sessionKeys = ['admin_email', 'admin_nom', 'admin_prenom', 'csrf_token'];
        foreach ($sessionKeys as $key) {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            }
        }
        
        // Destruction de la session si elle est vide
        if (empty($_SESSION)) {
            session_destroy();
        }
        
        // Suppression du cookie
        if (isset($_COOKIE['login_admin'])) {
            setcookie('login_admin', '', time() - 3600, '/', '', false, true);
        }
        
        // Redirection vers la page d'accueil avec nettoyage complet
        header("Location: index.php");
        exit;
    }

    /**
     * Interface de modification des horaires
     */
    public function modifierLesHoraires() {
        if (!$this->estConnecte()) {
            $this->redirigerVersConnexion();
            return;
        }

        // Pour l'instant, afficher une page simple
        $this->chargerVueAdmin('v_modifier_horaire.inc.php');
    }

    /**
     * Gestion du profil administrateur
     */
    public function gererProfil() {
        if (!$this->estConnecte()) {
            $this->redirigerVersConnexion();
            return;
        }

        // Récupérer les informations actuelles de l'admin
        $adminInfo = $this->recupererInfoAdmin();
        
        // Traitement du formulaire de modification
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resultat = $this->traiterModificationProfil();
            if ($resultat['succes']) {
                $_SESSION['message_succes'] = $resultat['message'];
            } else {
                $_SESSION['message_erreur'] = $resultat['message'];
            }
            header("Location: index.php?controleur=Admin&action=gererProfil");
            exit;
        }

        // Variables pour le layout admin
        $pageActive = 'profil';
        $titrePage = 'Gestion de mon compte - Administration';
        $messageSucces = isset($_SESSION['message_succes']) ? $_SESSION['message_succes'] : '';
        $messageErreur = isset($_SESSION['message_erreur']) ? $_SESSION['message_erreur'] : '';
        unset($_SESSION['message_succes'], $_SESSION['message_erreur']);
        
        // Capturer le contenu de la vue
        ob_start();
        include_once("application/vues/partie_admin/v_gerer_profil.inc.php");
        $contenu = ob_get_clean();
        
        // Charger le layout avec le contenu
        require_once Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
    }

    // ========== MÉTHODES PRIVÉES ==========

    /**
     * Vérifie si l'utilisateur est connecté
     */
    private function estConnecte(): bool {
        return isset($_SESSION['login_admin']) && !empty($_SESSION['login_admin']);
    }

    /**
     * Génère un token CSRF
     */
    private function genererTokenCSRF(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Filtre les entrées utilisateur
     */
    private function filtrerEntree(string $input): string {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Connecte l'utilisateur
     */
    private function connecterUtilisateur(string $login): void {
        $_SESSION['login_admin'] = $login;
        $_SESSION['derniere_activite'] = time();
        
        // Initialiser les informations par défaut si elles n'existent pas
        if (!isset($_SESSION['admin_email'])) {
            $_SESSION['admin_email'] = 'admin@trhairdesign.fr';
        }
        if (!isset($_SESSION['admin_nom'])) {
            $_SESSION['admin_nom'] = 'Administrateur';
        }
        if (!isset($_SESSION['admin_prenom'])) {
            $_SESSION['admin_prenom'] = 'TR Hair Design';
        }
    }

    /**
     * Définit le cookie de connexion automatique
     */
    private function definirCookieConnexion(string $login): void {
        $valeur = base64_encode($login . '|' . time());
        setcookie('login_admin', $valeur, time() + (7 * 24 * 3600), '/', '', false, true);
    }

    /**
     * Vérifie le cookie de connexion automatique
     */
    private function verifierCookieConnexion(): void {
        if (!$this->estConnecte() && isset($_COOKIE['login_admin'])) {
            $donnees = base64_decode($_COOKIE['login_admin']);
            $parties = explode('|', $donnees);
            
            if (count($parties) === 2) {
                $login = $parties[0];
                $timestamp = (int)$parties[1];
                
                // Vérifier que le cookie n'est pas expiré (7 jours)
                if (time() - $timestamp < (7 * 24 * 3600)) {
                    $_SESSION['login_admin'] = $login;
                }
            }
        }
    }

    /**
     * Charge une vue d'administration
     */
    private function chargerVueAdmin(string $vue, array $donnees = []): void {
        $cheminVue = Chemins::VUES_ADMIN . $vue;
        if (file_exists($cheminVue)) {
            // Extraire les variables pour la vue
            foreach ($donnees as $key => $value) {
                $$key = $value;
            }
            require $cheminVue;
        } else {
            echo "<div style='padding: 50px; text-align: center; font-family: Arial, sans-serif;'>";
            echo "<h2>Erreur 404</h2>";
            echo "<p>Vue introuvable : $vue</p>";
            echo "<a href='index.php'>Retour à l'accueil</a>";
            echo "</div>";
        }
    }

    /**
     * Charge une vue d'administration avec le layout commun
     */
    private function chargerVueAdminAvecLayout(string $vue, string $pageActive, array $donnees = []): void {
        $cheminVue = Chemins::VUES_ADMIN . $vue;
        if (file_exists($cheminVue)) {
            // Extraire les variables pour la vue
            foreach ($donnees as $key => $value) {
                $$key = $value;
            }
            
            // Capturer le contenu de la vue
            ob_start();
            require $cheminVue;
            $contenu = ob_get_clean();
            
            // Charger le layout avec le contenu
            require Chemins::VUES_ADMIN . 'v_layout_admin.inc.php';
        } else {
            echo "<div style='padding: 50px; text-align: center; font-family: Arial, sans-serif;'>";
            echo "<h2>Erreur 404</h2>";
            echo "<p>Vue introuvable : $vue</p>";
            echo "<a href='index.php'>Retour à l'accueil</a>";
            echo "</div>";
        }
    }

    /**
     * Affiche une erreur
     */
    private function afficherErreur(string $message): void {
        echo "<div style='padding: 50px; text-align: center; font-family: Arial, sans-serif; color: red;'>";
        echo "<h2>Erreur</h2>";
        echo "<p>" . htmlspecialchars($message) . "</p>";
        echo "<a href='index.php?controleur=Admin&action=afficherIndex'>Retour à la connexion</a>";
        echo "</div>";
    }

    /**
     * Redirige vers la page de connexion
     */
    private function redirigerVersConnexion(): void {
        header("Location: index.php?controleur=Admin&action=afficherIndex");
        exit;
    }

    /**
     * Retourne le token CSRF pour les formulaires
     */
    public function getTokenCSRF(): string {
        return $this->genererTokenCSRF();
    }

    /**
     * Récupère les statistiques depuis la base de données
     */
    private function recupererStatistiques() {
        $statistiques = [];
        
        try {
            // Rendez-vous de cette semaine
            if (class_exists('gestion_rendezvous')) {
                $gestionRDV = new gestion_rendezvous();
                $rdvCetteSemaine = $gestionRDV->obtenirRendezVousCetteSemaine();
                $statistiques['rendez_vous'] = count($rdvCetteSemaine);
                $statistiques['rdv_tendance'] = $gestionRDV->calculerTendanceRendezVous();
            } else {
                $statistiques['rendez_vous'] = 0;
                $statistiques['rdv_tendance'] = 0;
            }
            
            // Services actifs
            if (class_exists('GestionProduits')) {
                $servicesActifs = GestionProduits::getLesProduits();
                $statistiques['services'] = count($servicesActifs);
                $statistiques['services_tendance'] = $this->calculerTendanceServices();
            } else {
                $statistiques['services'] = 0;
                $statistiques['services_tendance'] = 0;
            }
            
            // Messages non lus
            if (class_exists('gestion_contact')) {
                $gestionContact = new gestion_contact();
                $messagesNonLus = $gestionContact->obtenirMessagesNonLus();
                $statistiques['messages'] = count($messagesNonLus);
            } else {
                $statistiques['messages'] = 0;
            }
            
            // Revenus du mois
            if (class_exists('gestion_rendezvous')) {
                $gestionRDV = new gestion_rendezvous();
                $revenusMois = $gestionRDV->calculerRevenusMois();
                $statistiques['revenus'] = $revenusMois;
                $statistiques['revenus_tendance'] = $gestionRDV->calculerTendanceRevenus();
            } else {
                $statistiques['revenus'] = 0;
                $statistiques['revenus_tendance'] = 0;
            }
            
        } catch (Exception $e) {
            // En cas d'erreur, utiliser des valeurs par défaut
            $statistiques = [
                'rendez_vous' => 0,
                'rdv_tendance' => 0,
                'services' => 0,
                'services_tendance' => 0,
                'messages' => 0,
                'revenus' => 0,
                'revenus_tendance' => 0
            ];
        }
        
        return $statistiques;
    }

    /**
     * Calcule la tendance des services
     */
    private function calculerTendanceServices() {
        // Logique pour calculer la tendance des services
        return rand(1, 5); // Placeholder
    }

    /**
     * Récupère les informations de l'administrateur
     */
    private function recupererInfoAdmin() {
        // Récupérer les informations depuis la session ou utiliser les valeurs par défaut
        return [
            'login' => $_SESSION['login_admin'] ?? 'admin',
            'email' => $_SESSION['admin_email'] ?? 'admin@trhairdesign.fr',
            'nom' => $_SESSION['admin_nom'] ?? 'Administrateur',
            'prenom' => $_SESSION['admin_prenom'] ?? 'TR Hair Design',
            'derniere_connexion' => date('d/m/Y à H:i', $_SESSION['derniere_activite'] ?? time()),
            'date_creation' => '01/01/2024',
            'role' => 'Administrateur principal'
        ];
    }

    /**
     * Traite la modification du profil administrateur
     */
    private function traiterModificationProfil() {
        $login = $this->filtrerEntree($_POST['login'] ?? '');
        $email = $this->filtrerEntree($_POST['email'] ?? '');
        $nom = $this->filtrerEntree($_POST['nom'] ?? '');
        $prenom = $this->filtrerEntree($_POST['prenom'] ?? '');
        $motDePasseActuel = $_POST['mot_de_passe_actuel'] ?? '';
        $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'] ?? '';
        $confirmationMotDePasse = $_POST['confirmation_mot_de_passe'] ?? '';

        // Validation des champs obligatoires
        if (empty($login) || empty($email) || empty($nom) || empty($prenom)) {
            return ['succes' => false, 'message' => 'Tous les champs obligatoires doivent être remplis.'];
        }

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['succes' => false, 'message' => 'L\'adresse email n\'est pas valide.'];
        }

        // Si un nouveau mot de passe est fourni
        if (!empty($nouveauMotDePasse)) {
            // Vérifier le mot de passe actuel
            if ($motDePasseActuel !== 'admin123') { // À remplacer par une vraie vérification
                return ['succes' => false, 'message' => 'Le mot de passe actuel est incorrect.'];
            }

            // Vérifier la confirmation
            if ($nouveauMotDePasse !== $confirmationMotDePasse) {
                return ['succes' => false, 'message' => 'La confirmation du nouveau mot de passe ne correspond pas.'];
            }

            // Vérifier la complexité du mot de passe
            if (strlen($nouveauMotDePasse) < 8) {
                return ['succes' => false, 'message' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.'];
            }
        }

        // Sauvegarder les modifications dans la session
        $_SESSION['login_admin'] = $login;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_nom'] = $nom;
        $_SESSION['admin_prenom'] = $prenom;
        
        // Mettre à jour la dernière activité
        $_SESSION['derniere_activite'] = time();
        
        return ['succes' => true, 'message' => 'Votre profil a été mis à jour avec succès.'];
    }
}
?>
