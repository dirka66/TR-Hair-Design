-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 08 jan. 2025 à 07:54
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdd_trhairdesign`
--

-- --------------------------------------------------------

--
-- Structure de la table `famille`
--

DROP TABLE IF EXISTS `famille`;
CREATE TABLE IF NOT EXISTS `famille` (
  `idFamille` int NOT NULL AUTO_INCREMENT,
  `nomFamille` varchar(50) NOT NULL,
  PRIMARY KEY (`idFamille`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `famille`
--

INSERT INTO `famille` (`idFamille`, `nomFamille`) VALUES
(1, 'Habits');

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

DROP TABLE IF EXISTS `horaire`;
CREATE TABLE IF NOT EXISTS `horaire` (
  `idHoraire` varchar(30) NOT NULL,
  `heureOuvertureMatin` time NOT NULL,
  `heureFermetureMatin` time NOT NULL,
  `heureOuvertureAprem` time NOT NULL,
  `heureFermetureAprem` time NOT NULL,
  `ferme` tinyint(1) NOT NULL DEFAULT '0',
  `fermeLundi` tinyint(1) DEFAULT '0',
  `fermeMardi` tinyint(1) DEFAULT '0',
  `fermeMercredi` tinyint(1) DEFAULT '0',
  `fermeJeudi` tinyint(1) DEFAULT '0',
  `fermeVendredi` tinyint(1) DEFAULT '0',
  `fermeSamedi` tinyint(1) DEFAULT '0',
  `fermeDimanche` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`idHoraire`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `horaire`
--

INSERT INTO `horaire` (`idHoraire`, `heureOuvertureMatin`, `heureFermetureMatin`, `heureOuvertureAprem`, `heureFermetureAprem`, `ferme`, `fermeLundi`, `fermeMardi`, `fermeMercredi`, `fermeJeudi`, `fermeVendredi`, `fermeSamedi`, `fermeDimanche`) VALUES
('Lundi', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 1, 0, 0, 0, 0, 0, 0),
('Mardi', '09:00:00', '12:00:00', '14:30:00', '20:00:00', 0, 0, 0, 0, 0, 0, 0, 0),
('Mercredi', '09:00:00', '12:00:00', '14:30:00', '20:00:00', 0, 0, 0, 0, 0, 0, 0, 0),
('Jeudi', '09:00:00', '12:00:00', '14:30:00', '20:00:00', 0, 0, 0, 0, 0, 0, 0, 0),
('Vendredi', '09:00:00', '12:00:00', '14:30:00', '20:00:00', 0, 0, 0, 0, 0, 0, 0, 0),
('Samedi', '09:00:00', '12:00:00', '14:30:00', '20:00:00', 0, 0, 0, 0, 0, 0, 0, 0),
('Dimanche', '09:00:00', '12:00:00', '14:30:00', '20:00:00', 0, 0, 0, 0, 0, 0, 0, 0),
('', '08:00:00', '12:00:00', '14:00:00', '18:00:00', 0, 0, 0, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `horaires`
--

DROP TABLE IF EXISTS `horaires`;
CREATE TABLE IF NOT EXISTS `horaires` (
  `idHoraire` int NOT NULL AUTO_INCREMENT,
  `ferme` tinyint(1) DEFAULT '0',
  `heureOuvertureMatin` time DEFAULT NULL,
  `heureFermetureMatin` time DEFAULT NULL,
  `heureOuvertureAprem` time DEFAULT NULL,
  `heureFermetureAprem` time DEFAULT NULL,
  PRIMARY KEY (`idHoraire`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `idImage` int NOT NULL AUTO_INCREMENT,
  `nomImage` varchar(255) NOT NULL,
  PRIMARY KEY (`idImage`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `information`
--

DROP TABLE IF EXISTS `information`;
CREATE TABLE IF NOT EXISTS `information` (
  `idInfo` int NOT NULL AUTO_INCREMENT,
  `titreInformation` varchar(50) NOT NULL,
  `libelleInformation` varchar(255) NOT NULL,
  PRIMARY KEY (`idInfo`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `information`
--

INSERT INTO `information` (`idInfo`, `titreInformation`, `libelleInformation`) VALUES
(1, 'Promotions', 'Soldes exclusives durant les fêtes de fin d\'année ! Jusqu\'à 25% de remise sur tous les nettoyages de vos vêtements.');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `idProduit` int NOT NULL AUTO_INCREMENT,
  `position` int NOT NULL,
  `nomProduit` varchar(50) NOT NULL,
  `prix` float NOT NULL,
  `unite` varchar(50) NOT NULL,
  `idFamille` int DEFAULT NULL,
  PRIMARY KEY (`idProduit`),
  KEY `fk_idFamille` (`idFamille`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`idProduit`, `position`, `nomProduit`, `prix`, `unite`, `idFamille`) VALUES
(2, 2, 'Doudoune plume', 16, '', NULL),
(1, 1, 'Manteau', 16, '', NULL),
(3, 3, 'Cravate', 0, '', NULL),
(4, 4, 'Couette synthétique', 20, '', NULL),
(5, 5, 'Chemisier', 0, '', NULL),
(6, 6, 'Robe', 0, '', NULL),
(7, 7, 'Blouson', 11, '', NULL),
(8, 8, 'Veste', 8.5, '', NULL),
(9, 9, 'Pantalon', 7.5, '', NULL),
(10, 10, 'Chemise', 0, '', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idUtilisateur` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `passe` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`idUtilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `login`, `passe`, `isAdmin`) VALUES
(1, 'grandChef', 'a227047a05306bce6fa7dc6d8f6dea6e9b01c9b0', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
