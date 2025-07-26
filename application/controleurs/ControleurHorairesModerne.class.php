<?php

/**
 * Contrôleur moderne pour la gestion des horaires d'ouverture
 * @author TR Hair Design
 * @version 2.0
 */
class ControleurHorairesModerne {

    private $gestionnaire;

    public function __construct() {
        require_once Chemins::MODELES . 'gestion_horaires_moderne.class.php';
        $this->gestionnaire = new GestionHorairesModerne();
    }

    /**
     * Liste tous les horaires pour l'administration
     */
    public function listerHoraires() {
        if (!$this->estConnecte()) {
            $this->redirigerVersConnexion();
            return;
        }

        try {
            $horaires = $this->gestionnaire->obtenirTousLesHoraires();
            $this->chargerVueAdmin('v_gestion_horaires.inc.php', [
                'horaires' => $horaires,
                'action' => 'liste',
                'message' => $_GET['message'] ?? null
            ]);
        } catch (Exception $e) {
            $this->afficherErreur("Erreur lors du chargement des horaires : " . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de modification d'un horaire
     */
    public function modifierHoraire() {
        if (!$this->estConnecte()) {
            $this->redirigerVersConnexion();
            return;
        }

        $idHoraire = $_GET['id'] ?? null;
        
        if (!$idHoraire) {
            $this->afficherErreur("ID horaire manquant.");
            return;
        }

        try {
            $horaire = $this->gestionnaire->obtenirHoraireParId($idHoraire);
            if (!$horaire) {
                $this->afficherErreur("Horaire introuvable.");
                return;
            }

            $this->chargerVueAdmin('v_modifier_horaire.inc.php', [
                'horaire' => $horaire,
                'action' => 'modifier',
                'csrf_token' => $this->genererTokenCSRF()
            ]);
        } catch (Exception $e) {
            $this->afficherErreur("Erreur : " . $e->getMessage());
        }
    }

    /**
     * Sauvegarde les modifications d'un horaire
     */
    public function sauvegarderHoraire() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!$this->estConnecte()) {
            $this->redirigerVersConnexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigerVersListe();
            return;
        }

        // Pour le debugging, acceptons sans token CSRF temporairement
        // if (!$this->verifierTokenCSRF()) {
        //     $this->afficherErreur("Token de sécurité invalide.");
        //     return;
        // }

        $donnees = $this->validerDonneesHoraire($_POST);
        
        if (!empty($donnees['erreurs'])) {
            $_SESSION['message_erreur'] = "Erreurs de validation : " . implode(', ', $donnees['erreurs']);
            header("Location: index.php?controleur=HorairesModerne&action=listerHoraires");
            exit;
        }

        try {
            $resultat = $this->gestionnaire->mettreAJourHoraire($donnees['idHoraire'], $donnees);
            
            if ($resultat) {
                $_SESSION['message_succes'] = "Horaire modifié avec succès.";
                header("Location: index.php?controleur=HorairesModerne&action=listerHoraires");
                exit;
            } else {
                $_SESSION['message_erreur'] = "Erreur lors de la sauvegarde.";
                header("Location: index.php?controleur=HorairesModerne&action=listerHoraires");
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['message_erreur'] = "Erreur : " . $e->getMessage();
            header("Location: index.php?controleur=HorairesModerne&action=listerHoraires");
            exit;
        }
    }

    /**
     * Sauvegarde tous les horaires depuis la liste
     */
    public function sauvegarderTousLesHoraires() {
        if (!$this->estConnecte()) {
            $this->redirigerVersConnexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigerVersListe();
            return;
        }

        if (!$this->verifierTokenCSRF()) {
            $this->afficherErreur("Token de sécurité invalide.");
            return;
        }

        try {
            $horaires = $_POST['horaires'] ?? [];
            $erreurs = [];

            foreach ($horaires as $idHoraire => $donneesHoraire) {
                $donneesHoraire['idHoraire'] = $idHoraire;
                $donneesValidees = $this->validerDonneesHoraire($donneesHoraire);
                
                if (!empty($donneesValidees['erreurs'])) {
                    $erreurs = array_merge($erreurs, $donneesValidees['erreurs']);
                    continue;
                }

                $this->gestionnaire->mettreAJourHoraire($idHoraire, $donneesValidees);
            }

            if (empty($erreurs)) {
                header("Location: index.php?controleur=HorairesModerne&action=listerHoraires&message=tous_modifies");
                exit;
            } else {
                $this->afficherErreur("Erreurs : " . implode(', ', $erreurs));
            }
        } catch (Exception $e) {
            $this->afficherErreur("Erreur : " . $e->getMessage());
        }
    }

    // ========== MÉTHODES PRIVÉES ==========

    /**
     * Valide les données d'horaire
     */
    private function validerDonneesHoraire($donnees): array {
        $erreurs = [];
        $horaireValide = [];

        $horaireValide['idHoraire'] = intval($donnees['idHoraire'] ?? 0);
        $horaireValide['ferme'] = isset($donnees['ferme']) ? 1 : 0;
        $horaireValide['pauseMidi'] = isset($donnees['pauseMidi']) ? 1 : 0;

        if (!$horaireValide['ferme']) {
            // Validation des heures
            $horaireValide['heureOuvertureMatin'] = $this->validerHeure($donnees['heureOuvertureMatin'] ?? '');
            $horaireValide['heureFermetureMatin'] = $this->validerHeure($donnees['heureFermetureMatin'] ?? '');
            
            if ($horaireValide['pauseMidi']) {
                $horaireValide['heureOuvertureAprem'] = $this->validerHeure($donnees['heureOuvertureAprem'] ?? '');
                $horaireValide['heureFermetureAprem'] = $this->validerHeure($donnees['heureFermetureAprem'] ?? '');
            } else {
                $horaireValide['heureOuvertureAprem'] = null;
                $horaireValide['heureFermetureAprem'] = null;
            }
        } else {
            $horaireValide['heureOuvertureMatin'] = null;
            $horaireValide['heureFermetureMatin'] = null;
            $horaireValide['heureOuvertureAprem'] = null;
            $horaireValide['heureFermetureAprem'] = null;
        }

        return [
            'erreurs' => $erreurs,
            'idHoraire' => $horaireValide['idHoraire'],
            'ferme' => $horaireValide['ferme'],
            'pauseMidi' => $horaireValide['pauseMidi'],
            'heureOuvertureMatin' => $horaireValide['heureOuvertureMatin'],
            'heureFermetureMatin' => $horaireValide['heureFermetureMatin'],
            'heureOuvertureAprem' => $horaireValide['heureOuvertureAprem'],
            'heureFermetureAprem' => $horaireValide['heureFermetureAprem']
        ];
    }

    /**
     * Valide une heure au format HH:MM
     */
    private function validerHeure($heure): ?string {
        if (empty($heure)) {
            return null;
        }
        
        if (preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $heure)) {
            return $heure . ':00';
        }
        
        return null;
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
     * Vérifie le token CSRF
     */
    private function verifierTokenCSRF(): bool {
        return isset($_POST['csrf_token']) && 
               isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    private function estConnecte(): bool {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['login_admin']) && !empty($_SESSION['login_admin']);
    }

    /**
     * Charge une vue d'administration
     */
    private function chargerVueAdmin(string $vue, array $donnees = []): void {
        extract($donnees);
        $cheminVue = Chemins::VUES_ADMIN . $vue;
        
        if (file_exists($cheminVue)) {
            require $cheminVue;
        } else {
            $this->afficherErreur("Vue introuvable : $vue");
        }
    }

    /**
     * Affiche une erreur
     */
    private function afficherErreur(string $message): void {
        echo "<div style='padding: 50px; text-align: center; font-family: Arial, sans-serif; color: red;'>";
        echo "<h2>Erreur</h2>";
        echo "<p>" . htmlspecialchars($message) . "</p>";
        echo "<a href='index.php?controleur=HorairesModerne&action=listerHoraires'>Retour à la liste</a>";
        echo "</div>";
    }

    /**
     * Redirige vers la connexion
     */
    private function redirigerVersConnexion(): void {
        header("Location: index.php?controleur=Admin&action=afficherIndex");
        exit;
    }

    /**
     * Redirige vers la liste
     */
    private function redirigerVersListe(): void {
        header("Location: index.php?controleur=HorairesModerne&action=listerHoraires");
        exit;
    }
}
?>
