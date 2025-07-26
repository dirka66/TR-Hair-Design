<?php
require_once __DIR__ . '/gestion_basededonnee.class.php';
class gestion_contact {
    
    private $cnx;
    
    public function __construct() {
        $this->cnx = GestionBaseDeDonnees::getConnexion();
    }
    
    public function obtenirTousLesMessages() {
        $sql = "SELECT * FROM messages ORDER BY date_creation DESC";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function ajouterMessage($nom, $prenom, $email, $telephone, $sujet, $message) {
        $sql = "INSERT INTO messages (nom, prenom, email, telephone, sujet, message, lu, date_creation) 
                VALUES (:nom, :prenom, :email, :telephone, :sujet, :message, 0, NOW())";
        $params = [
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':sujet' => $sujet,
            ':message' => $message
        ];
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function marquerCommeLu($id) {
        $sql = "UPDATE messages SET lu = 1 WHERE id_message = :id";
        $params = [':id' => $id];
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function supprimerMessage($id) {
        $sql = "DELETE FROM messages WHERE id_message = :id";
        $params = [':id' => $id];
        $stmt = $this->cnx->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function obtenirMessageParId($id) {
        $sql = "SELECT * FROM messages WHERE id_message = :id";
        $params = [':id' => $id];
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute($params);
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return !empty($resultat) ? $resultat[0] : null;
    }
    
    public function obtenirStatistiquesMessages() {
        $stats = [];
        $sql = "SELECT COUNT(*) as total FROM messages";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats['total'] = $resultat[0]['total'];
        $sql = "SELECT COUNT(*) as non_lus FROM messages WHERE lu = 0";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats['non_lus'] = $resultat[0]['non_lus'];
        $sql = "SELECT COUNT(*) as lus FROM messages WHERE lu = 1";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats['lus'] = $resultat[0]['lus'];
        $sql = "SELECT COUNT(*) as cette_semaine FROM messages WHERE date_creation >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        $resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stats['cette_semaine'] = $resultat[0]['cette_semaine'];
        return $stats;
    }
    
    public function obtenirMessagesNonLus() {
        $sql = "SELECT * FROM messages WHERE lu = 0 ORDER BY date_creation DESC";
        $stmt = $this->cnx->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
