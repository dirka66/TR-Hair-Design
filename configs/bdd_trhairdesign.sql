-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 26 juil. 2025 à 18:01
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

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
  `description` text,
  `icone` varchar(50) DEFAULT 'fas fa-scissors',
  `couleur` varchar(7) DEFAULT '#4e73df',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idFamille`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `famille`
--

INSERT INTO `famille` (`idFamille`, `nomFamille`, `description`, `icone`, `couleur`, `actif`) VALUES
(1, 'Coupe & Style', 'Coupes modernes et styling professionnel', 'fas fa-cut', '#e74c3c', 1),
(2, 'Coloration', 'Colorations, mèches et balayages', 'fas fa-palette', '#9b59b6', 1),
(3, 'Soins Capillaires', 'Masques, soins réparateurs et traitements', 'fas fa-leaf', '#27ae60', 1),
(4, 'Coiffure Événementielle', 'Mariages, soirées et occasions spéciales', 'fas fa-star', '#f39c12', 1),
(5, 'Barbier', 'Services spécialisés pour hommes', 'fas fa-user-tie', '#34495e', 1),
(8, 'Exemple catégorie', NULL, 'fas fa-scissors', '#4e73df', 1);

-- --------------------------------------------------------

--
-- Structure de la table `galerie`
--

DROP TABLE IF EXISTS `galerie`;
CREATE TABLE IF NOT EXISTS `galerie` (
  `idImage` int NOT NULL AUTO_INCREMENT,
  `nomImage` varchar(255) NOT NULL,
  `titreImage` varchar(100) DEFAULT NULL,
  `description` text,
  `cheminImage` varchar(500) NOT NULL,
  `taille` int DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `ordre` int NOT NULL DEFAULT '0',
  `dateAjout` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idImage`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

DROP TABLE IF EXISTS `horaire`;
CREATE TABLE IF NOT EXISTS `horaire` (
  `idHoraire` int NOT NULL AUTO_INCREMENT,
  `jour` varchar(20) NOT NULL,
  `numeroJour` int NOT NULL,
  `heureOuvertureMatin` time DEFAULT NULL,
  `heureFermetureMatin` time DEFAULT NULL,
  `heureOuvertureAprem` time DEFAULT NULL,
  `heureFermetureAprem` time DEFAULT NULL,
  `ferme` tinyint(1) NOT NULL DEFAULT '0',
  `pauseMidi` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idHoraire`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `horaire`
--

INSERT INTO `horaire` (`idHoraire`, `jour`, `numeroJour`, `heureOuvertureMatin`, `heureFermetureMatin`, `heureOuvertureAprem`, `heureFermetureAprem`, `ferme`, `pauseMidi`) VALUES
(1, 'Lundi', 1, '00:00:00', '00:00:00', '00:00:00', '00:00:00', 1, 0),
(2, 'Mardi', 2, '09:00:00', '12:00:00', '14:00:00', '19:00:00', 0, 1),
(3, 'Mercredi', 3, '09:00:00', '12:00:00', '14:00:00', '19:00:00', 0, 1),
(4, 'Jeudi', 4, '09:00:00', '12:00:00', '14:00:00', '19:00:00', 0, 1),
(5, 'Vendredi', 5, '09:00:00', '12:00:00', '14:00:00', '19:00:00', 0, 1),
(6, 'Samedi', 6, '09:00:00', '12:00:00', '14:00:00', '19:00:00', 0, 1),
(7, 'Dimanche', 7, '09:00:00', '12:00:00', '14:00:00', '19:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `information`
--

DROP TABLE IF EXISTS `information`;
CREATE TABLE IF NOT EXISTS `information` (
  `idInfo` int NOT NULL AUTO_INCREMENT,
  `titreInformation` varchar(100) NOT NULL,
  `libelleInformation` text NOT NULL,
  `dateCreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateModification` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `important` tinyint(1) NOT NULL DEFAULT '0',
  `couleur` varchar(7) DEFAULT '#4e73df',
  PRIMARY KEY (`idInfo`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `information`
--

INSERT INTO `information` (`idInfo`, `titreInformation`, `libelleInformation`, `dateCreation`, `dateModification`, `actif`, `important`, `couleur`) VALUES
(1, 'Nouveau salon TR Hair Design !', 'Nous sommes ravis de vous accueillir dans notre nouveau salon de coiffure moderne. Venez découvrir nos prestations haut de gamme dans un cadre chaleureux et professionnel.', '2025-01-25 10:00:00', NULL, 1, 1, '#e74c3c'),
(2, 'Promotion du mois', 'Tout le mois de janvier, profitez de -20% sur toutes nos colorations ! Prenez rendez-vous dès maintenant.', '2025-01-25 10:15:00', NULL, 1, 0, '#f39c12'),
(3, 'Horaires spéciaux', 'Attention : Le salon sera fermé exceptionnellement le 14 février pour formation de l\\\'équipe. Merci de votre compréhension.', '2025-01-25 10:30:00', '2025-07-26 19:35:38', 1, 1, '#3498db');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `sujet` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `lu` tinyint(1) NOT NULL DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_message`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id_message`, `nom`, `prenom`, `email`, `telephone`, `sujet`, `message`, `lu`, `date_creation`) VALUES
(1, 'Martin', 'Sophie', 'sophie.martin@email.fr', '06 12 34 56 78', 'Demande d\'information', 'Bonjour, j\'aimerais connaître vos tarifs pour une coloration complète et un balayage. Merci !', 0, '2025-07-25 10:15:00'),
(2, 'Dubois', 'Pierre', 'pierre.dubois@gmail.com', '07 98 76 54 32', 'Prise de rendez-vous', 'Salut ! Est-ce possible d\'avoir un rdv cette semaine pour une coupe homme ? Je suis assez flexible sur les horaires.', 1, '2025-07-25 09:30:00'),
(3, 'Leclerc', 'Marie', 'marie.leclerc@outlook.fr', '06 45 67 89 12', 'Réclamation', 'J\'ai eu un problème avec ma dernière coloration qui n\'a pas bien pris. Pouvez-vous me recontacter svp ?', 1, '2025-07-24 16:45:00'),
(4, 'Bernard', 'Lucas', 'lucas.bernard@email.com', '07 23 45 67 89', 'Compliment', 'Je tenais à vous remercier pour l\'excellent travail sur ma coupe ! Je recommande vivement votre salon.', 1, '2025-07-25 14:20:00'),
(5, 'Rousseau', 'Emma', 'emma.rousseau@gmail.fr', NULL, 'Autre', 'Bonjour, organisez-vous des formations en coiffure ? J\'aimerais me reconvertir dans ce domaine.', 0, '2025-07-25 11:10:00');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `idProduit` int NOT NULL AUTO_INCREMENT,
  `position` int NOT NULL DEFAULT '0',
  `nomProduit` varchar(100) NOT NULL,
  `description` text,
  `prix` decimal(6,2) NOT NULL,
  `duree` int DEFAULT '30',
  `unite` varchar(50) NOT NULL DEFAULT 'prestation',
  `idFamille` int DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `populaire` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idProduit`),
  KEY `fk_idFamille` (`idFamille`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`idProduit`, `position`, `nomProduit`, `description`, `prix`, `duree`, `unite`, `idFamille`, `actif`, `populaire`) VALUES
(1, 13, 'Coupe Femme', 'Coupe moderne avec styling et brushing', 35.00, 45, 'prestation', 1, 1, 1),
(2, 2, 'Coupe Homme', 'Coupe masculine tendance', 25.00, 30, 'prestation', 1, 1, 1),
(3, 3, 'Brushing', 'Mise en forme et coiffage professionnel', 20.00, 30, 'prestation', 1, 1, 0),
(4, 4, 'Coloration complète', 'Coloration racines et longueurs', 65.00, 120, 'prestation', 2, 1, 1),
(5, 5, 'Mèches', 'Mèches sur papier ou bonnet', 75.00, 150, 'prestation', 2, 1, 0),
(6, 6, 'Balayage', 'Technique de balayage naturel', 85.00, 180, 'prestation', 2, 1, 1),
(7, 7, 'Shampooing + Soin', 'Lavage et soin adapté à votre type de cheveux', 15.00, 20, 'prestation', 3, 1, 0),
(8, 8, 'Masque réparateur', 'Soin intensif pour cheveux abîmés', 25.00, 30, 'prestation', 3, 1, 0),
(9, 9, 'Chignon de mariée', 'Coiffure élégante pour votre jour J', 120.00, 90, 'prestation', 4, 1, 0),
(10, 10, 'Coiffure de soirée', 'Mise en beauté pour vos événements', 60.00, 60, 'prestation', 4, 1, 0),
(11, 11, 'Barbe + Moustache', 'Taille et entretien de la barbe', 20.00, 25, 'prestation', 5, 1, 0),
(12, 12, 'Coupe + Barbe', 'Formule complète homme', 40.00, 50, 'prestation', 5, 1, 1),
(25, 1, 'Shampoing & Coupe Homme', 'Coupe moderne, shampoing inclus, conseils personnalisés.', 25.00, 30, '30', 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `rendez_vous`
--

DROP TABLE IF EXISTS `rendez_vous`;
CREATE TABLE IF NOT EXISTS `rendez_vous` (
  `idRendezVous` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dateRendezVous` datetime NOT NULL,
  `duree` int NOT NULL DEFAULT '30',
  `idProduit` int DEFAULT NULL,
  `commentaire` text,
  `statut` enum('attente','confirme','annule','termine') NOT NULL DEFAULT 'attente',
  `dateCreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idRendezVous`),
  KEY `fk_rdv_produit` (`idProduit`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `rendez_vous`
--

INSERT INTO `rendez_vous` (`idRendezVous`, `nom`, `prenom`, `telephone`, `email`, `dateRendezVous`, `duree`, `idProduit`, `commentaire`, `statut`, `dateCreation`) VALUES
(1, 'Garcia', 'Isabella', '06 87 65 43 21', 'isabella.garcia@email.fr', '2025-07-26 10:00:00', 45, 1, 'Première fois dans votre salon, j\'ai les cheveux bouclés', 'attente', '2025-07-25 09:15:00'),
(2, 'Moreau', 'Alexandre', '07 12 34 56 78', 'alex.moreau@gmail.com', '2025-07-26 14:30:00', 50, 12, 'Coupe + barbe comme d\'habitude, merci !', 'confirme', '2025-07-25 08:45:00'),
(3, 'Leroy', 'Camille', '06 98 76 54 32', 'camille.leroy@outlook.fr', '2025-07-27 09:30:00', 180, 6, 'Balayage blond miel sur cheveux châtains', 'attente', '2025-07-25 10:30:00'),
(4, 'Thomas', 'Julien', '07 45 67 89 12', NULL, '2025-07-27 16:00:00', 30, 2, NULL, 'confirme', '2025-07-25 11:20:00'),
(5, 'Petit', 'Sarah', '06 23 45 67 89', 'sarah.petit@email.com', '2025-07-28 11:00:00', 120, 4, 'Retouche racines couleur châtain foncé', 'attente', '2025-07-25 12:00:00'),
(6, 'Roux', 'David', '07 67 89 12 34', 'david.roux@gmail.fr', '2025-07-25 18:00:00', 25, 11, 'Juste la barbe svp', 'termine', '2025-07-24 15:30:00'),
(7, 'Michel', 'Laura', '06 34 56 78 90', 'laura.michel@outlook.com', '2025-07-29 15:30:00', 90, 9, 'Chignon pour mariage de ma sœur samedi', 'attente', '2025-07-25 13:45:00'),
(8, 'Cetintas', 'Kadir', '07 82 91 61 64', 'kadircetintas023@gmail.com', '2025-07-26 15:00:00', 30, 12, 'test', 'confirme', '2025-07-25 18:54:58');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idUtilisateur` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `passe` varchar(255) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '1',
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `dateCreation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `derniereConnexion` datetime DEFAULT NULL,
  PRIMARY KEY (`idUtilisateur`),
  UNIQUE KEY `login_unique` (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUtilisateur`, `login`, `passe`, `nom`, `prenom`, `email`, `isAdmin`, `actif`, `dateCreation`, `derniereConnexion`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrateur', 'TR Hair Design', 'admin@trhairdesign.fr', 1, 1, '2025-01-25 10:00:00', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
