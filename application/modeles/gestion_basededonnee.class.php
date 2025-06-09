<?php

class GestionBaseDeDonnees {
    private static $pdo;

    public static function getConnexion() {
        // Vérifier si la connexion PDO est déjà établie
        if (self::$pdo === null) {
            // Utiliser les paramètres définis dans MysqlConfig pour se connecter
            self::$pdo = new PDO(
                'mysql:host=' . MysqlConfig::SERVEUR . ';dbname=' . MysqlConfig::BASE,
                MysqlConfig::UTILISATEUR,
                MysqlConfig::MOT_DE_PASSE
            );
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}
