<?php
require_once __DIR__ . '/gestion_basededonnee.class.php';
class gestion_rendezvous {
    
    private $cnx;
    
    public function __construct() {
        $this->cnx = GestionBaseDeDonnees::getConnexion();
    }
    
    public function obtenirTousLesRendezVous() {
        $sql = "SELECT r.*, p.nomProduit, p.prix 
                FROM rendez_vous r
                LEFT JOIN produit p ON r.idProduit = p.idProduit
                ORDER BY r.dateRendezVous DESC";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function ajouterRendezVous($nom, $prenom, $telephone, $email, $date_rdv, $heure_rdv, $service_id, $message = '') {
        $dateTime = $date_rdv . ' ' . $heure_rdv;
        $sql = "INSERT INTO rendez_vous (nom, prenom, telephone, email, dateRendezVous, idProduit, commentaire, statut, dateCreation) 
                VALUES (:nom, :prenom, :telephone, :email, :dateRendezVous, :idProduit, :commentaire, 'attente', NOW())";
        $params = [
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':telephone' => $telephone,
            ':email' => $email,
            ':dateRendezVous' => $dateTime,
            ':idProduit' => $service_id,
            ':commentaire' => $message
        ];
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function confirmerRendezVous($id) {
        $sql = "UPDATE rendez_vous SET statut = 'confirme' WHERE idRendezVous = :id";
        $params = [':id' => $id];
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function supprimerRendezVous($id) {
        $sql = "DELETE FROM rendez_vous WHERE idRendezVous = :id";
        $params = [':id' => $id];
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function obtenirCreneauxDisponibles($date) {
        try {
            // Récupérer le jour de la semaine (1 = Lundi, 7 = Dimanche)
            $jourSemaine = date('N', strtotime($date));
            
            // Récupérer les horaires d'ouverture pour ce jour
            $sql = "SELECT 
                        heureOuvertureMatin, 
                        heureFermetureMatin, 
                        heureOuvertureAprem, 
                        heureFermetureAprem, 
                        ferme 
                    FROM horaire 
                    WHERE numeroJour = :jour";
            $stmt = $this->cnx->prepare($sql);
            $stmt->execute([':jour' => $jourSemaine]);
            $horaire = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si le salon est fermé ce jour-là
            if (!$horaire || $horaire['ferme'] == 1) {
                return [];
            }
            
            // Générer les créneaux disponibles selon les horaires d'ouverture
            $creneauxDisponibles = [];
            
            // Créneaux du matin
            if (!empty($horaire['heureOuvertureMatin']) && !empty($horaire['heureFermetureMatin'])) {
                $debutMatin = (int)substr($horaire['heureOuvertureMatin'], 0, 2);
                $finMatin = (int)substr($horaire['heureFermetureMatin'], 0, 2);
                
                for ($heure = $debutMatin; $heure < $finMatin; $heure++) {
                    $creneauxDisponibles[] = sprintf("%02d:00", $heure);
                    $creneauxDisponibles[] = sprintf("%02d:30", $heure);
                }
            }
            
            // Créneaux de l'après-midi
            if (!empty($horaire['heureOuvertureAprem']) && !empty($horaire['heureFermetureAprem'])) {
                $debutAprem = (int)substr($horaire['heureOuvertureAprem'], 0, 2);
                $finAprem = (int)substr($horaire['heureFermetureAprem'], 0, 2);
                
                for ($heure = $debutAprem; $heure < $finAprem; $heure++) {
                    $creneauxDisponibles[] = sprintf("%02d:00", $heure);
                    $creneauxDisponibles[] = sprintf("%02d:30", $heure);
                }
            }
            
            // Si aucun horaire configuré, utiliser des créneaux par défaut
            if (empty($creneauxDisponibles)) {
                for ($heure = 9; $heure < 19; $heure++) {
                    $creneauxDisponibles[] = sprintf("%02d:00", $heure);
                    $creneauxDisponibles[] = sprintf("%02d:30", $heure);
                }
            }
            
            // Récupérer les rendez-vous déjà pris pour cette date
            $sql = "SELECT TIME(dateRendezVous) as heure_rdv 
                    FROM rendez_vous 
                    WHERE DATE(dateRendezVous) = :date 
                    AND statut NOT IN ('annule', 'supprime')";
            $stmt = $this->cnx->prepare($sql);
            $stmt->execute([':date' => $date]);
            $rendezVousPris = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Filtrer les créneaux déjà pris
            $heuresPrises = [];
            foreach ($rendezVousPris as $rdv) {
                $heuresPrises[] = substr($rdv['heure_rdv'], 0, 5);
            }
            
            $creneauxDisponibles = array_diff($creneauxDisponibles, $heuresPrises);
            
            // Trier les créneaux par ordre chronologique
            sort($creneauxDisponibles);
            
            return array_values($creneauxDisponibles);
            
        } catch (Exception $e) {
            error_log("Erreur dans obtenirCreneauxDisponibles: " . $e->getMessage());
            // En cas d'erreur, retourner des créneaux par défaut
            $creneauxStandards = [];
            for ($heure = 9; $heure < 19; $heure++) {
                $creneauxStandards[] = sprintf("%02d:00", $heure);
                $creneauxStandards[] = sprintf("%02d:30", $heure);
            }
            return $creneauxStandards;
        }
    }
    
    public function obtenirRendezVousParId($id) {
        $sql = "SELECT r.*, p.nomProduit, p.prix 
                FROM rendez_vous r
                LEFT JOIN produit p ON r.idProduit = p.idProduit
                WHERE r.idRendezVous = :id";
        $params = [':id' => $id];
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute($params);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return !empty($resultat) ? $resultat[0] : null;
    }
    
    public function obtenirStatistiquesRendezVous() {
        $stats = [];
        $sql = "SELECT COUNT(*) as total FROM rendez_vous";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats['total'] = $resultat[0]['total'];
        $sql = "SELECT COUNT(*) as en_attente FROM rendez_vous WHERE statut = 'attente'";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats['en_attente'] = $resultat[0]['en_attente'];
        $sql = "SELECT COUNT(*) as confirmes FROM rendez_vous WHERE statut = 'confirme'";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats['confirmes'] = $resultat[0]['confirmes'];
        $sql = "SELECT COUNT(*) as aujourd_hui FROM rendez_vous WHERE DATE(dateRendezVous) = CURDATE()";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats['aujourd_hui'] = $resultat[0]['aujourd_hui'];
        return $stats;
    }

    /**
     * Obtient les rendez-vous de cette semaine
     */
    public function obtenirRendezVousCetteSemaine() {
        $sql = "SELECT r.*, p.nomProduit, p.prix 
                FROM rendez_vous r
                LEFT JOIN produit p ON r.idProduit = p.idProduit
                WHERE YEARWEEK(r.dateRendezVous, 1) = YEARWEEK(CURDATE(), 1)
                ORDER BY r.dateRendezVous ASC";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule la tendance des rendez-vous (comparaison avec la semaine précédente)
     */
    public function calculerTendanceRendezVous() {
        // Rendez-vous cette semaine
        $sql = "SELECT COUNT(*) as count FROM rendez_vous 
                WHERE YEARWEEK(dateRendezVous, 1) = YEARWEEK(CURDATE(), 1)";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $cetteSemaine = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Rendez-vous semaine précédente
        $sql = "SELECT COUNT(*) as count FROM rendez_vous 
                WHERE YEARWEEK(dateRendezVous, 1) = YEARWEEK(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1)";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $semainePrecedente = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        if ($semainePrecedente == 0) {
            return $cetteSemaine > 0 ? 100 : 0;
        }

        $tendance = (($cetteSemaine - $semainePrecedente) / $semainePrecedente) * 100;
        return round($tendance);
    }

    /**
     * Calcule les revenus du mois en cours
     */
    public function calculerRevenusMois() {
        $sql = "SELECT SUM(p.prix) as total 
                FROM rendez_vous r
                LEFT JOIN produit p ON r.idProduit = p.idProduit
                WHERE MONTH(r.dateRendezVous) = MONTH(CURDATE()) 
                AND YEAR(r.dateRendezVous) = YEAR(CURDATE())
                AND r.statut IN ('confirme', 'termine')";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultat['total'] ?? 0;
    }

    /**
     * Calcule la tendance des revenus (comparaison avec le mois précédent)
     */
    public function calculerTendanceRevenus() {
        // Revenus ce mois
        $sql = "SELECT SUM(p.prix) as total 
                FROM rendez_vous r
                LEFT JOIN produit p ON r.idProduit = p.idProduit
                WHERE MONTH(r.dateRendezVous) = MONTH(CURDATE()) 
                AND YEAR(r.dateRendezVous) = YEAR(CURDATE())
                AND r.statut IN ('confirme', 'termine')";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $ceMois = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Revenus mois précédent
        $sql = "SELECT SUM(p.prix) as total 
                FROM rendez_vous r
                LEFT JOIN produit p ON r.idProduit = p.idProduit
                WHERE MONTH(r.dateRendezVous) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) 
                AND YEAR(r.dateRendezVous) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
                AND r.statut IN ('confirme', 'termine')";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $moisPrecedent = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        if ($moisPrecedent == 0) {
            return $ceMois > 0 ? 100 : 0;
        }

        $tendance = (($ceMois - $moisPrecedent) / $moisPrecedent) * 100;
        return round($tendance);
    }
}
?>
