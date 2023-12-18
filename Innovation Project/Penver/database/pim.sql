-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 04 déc. 2023 à 14:26
-- Version du serveur : 8.0.27
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pim`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie_jeux`
--

DROP TABLE IF EXISTS `categorie_jeux`;
CREATE TABLE IF NOT EXISTS `categorie_jeux` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `lib_categorie` int NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `feed_global`
--

DROP TABLE IF EXISTS `feed_global`;
CREATE TABLE IF NOT EXISTS `feed_global` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `message` varchar(254) NOT NULL,
  `id_utilisateur` int NOT NULL,
  `image` blob NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `Id_User` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `feed_officiel`
--

DROP TABLE IF EXISTS `feed_officiel`;
CREATE TABLE IF NOT EXISTS `feed_officiel` (
  `id_message` int NOT NULL,
  `id_studio` int NOT NULL,
  `reactions` int NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `Id_Studio` (`id_studio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `jeux`
--

DROP TABLE IF EXISTS `jeux`;
CREATE TABLE IF NOT EXISTS `jeux` (
  `id_jeux` int NOT NULL AUTO_INCREMENT,
  `id_categorie` int NOT NULL,
  `titre` varchar(254) NOT NULL,
  `id_studio` int NOT NULL,
  `categories` varchar(254) NOT NULL,
  `date_sortie` date NOT NULL,
  `jacket_jeux` blob NOT NULL,
  PRIMARY KEY (`id_jeux`),
  KEY `Categorie` (`id_categorie`),
  KEY `Studio` (`id_studio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `id_user1` int NOT NULL,
  `id_user2` int NOT NULL,
  `message` varchar(254) NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `Id_User1` (`id_user1`),
  KEY `Id_User2` (`id_user2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `studio`
--

DROP TABLE IF EXISTS `studio`;
CREATE TABLE IF NOT EXISTS `studio` (
  `id_studio` int NOT NULL AUTO_INCREMENT,
  `nom_studio` varchar(254) NOT NULL,
  `date_creation` date NOT NULL,
  `siege_social` varchar(254) NOT NULL,
  PRIMARY KEY (`id_studio`),
  UNIQUE KEY `Studio unique` (`nom_studio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `Pseudo` varchar(254) NOT NULL,
  `Mail` varchar(254) NOT NULL,
  `MDP` varchar(254) NOT NULL,
  `PP` blob NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `Mail_Unique` (`Mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `feed_global`
--
ALTER TABLE `feed_global`
  ADD CONSTRAINT `Id_User` FOREIGN KEY (`id_utilisateur`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `feed_officiel`
--
ALTER TABLE `feed_officiel`
  ADD CONSTRAINT `Id_Studio` FOREIGN KEY (`id_studio`) REFERENCES `studio` (`id_studio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `jeux`
--
ALTER TABLE `jeux`
  ADD CONSTRAINT `Categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie_jeux` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Studio` FOREIGN KEY (`id_studio`) REFERENCES `studio` (`id_studio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `Id_User1` FOREIGN KEY (`id_user1`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Id_User2` FOREIGN KEY (`id_user2`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
