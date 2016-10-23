-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Dim 23 Octobre 2016 à 17:28
-- Version du serveur: 5.1.73
-- Version de PHP: 5.5.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `railot`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `idCategorie` int(11) NOT NULL AUTO_INCREMENT,
  `label` text NOT NULL,
  PRIMARY KEY (`idCategorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`idCategorie`, `label`) VALUES
(1, 'Défaut');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE IF NOT EXISTS `commandes` (
  `idCommande` int(11) NOT NULL AUTO_INCREMENT,
  `idUtilisateur` int(11) NOT NULL,
  `dateCommande` datetime NOT NULL,
  `prixTotal` int(11) NOT NULL,
  PRIMARY KEY (`idCommande`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `commandes`
--


-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

CREATE TABLE IF NOT EXISTS `livraisons` (
  `idLivraison` int(11) NOT NULL AUTO_INCREMENT,
  `idCommande` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `etatCommande` text NOT NULL,
  `modeLivraison` int(11) NOT NULL,
  PRIMARY KEY (`idLivraison`),
  KEY `modeLivraison` (`modeLivraison`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `livraisons`
--


-- --------------------------------------------------------

--
-- Structure de la table `modesLivraisons`
--

CREATE TABLE IF NOT EXISTS `modesLivraisons` (
  `idModeLivraison` int(11) NOT NULL AUTO_INCREMENT,
  `label` text NOT NULL,
  PRIMARY KEY (`idModeLivraison`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `modesLivraisons`
--


-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE IF NOT EXISTS `produits` (
  `idProduit` int(11) NOT NULL AUTO_INCREMENT,
  `label` text NOT NULL,
  `categorieProduit` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `prix` int(11) NOT NULL,
  PRIMARY KEY (`idProduit`),
  KEY `categorieProduit` (`categorieProduit`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `produits`
--

INSERT INTO `produits` (`idProduit`, `label`, `categorieProduit`, `description`, `prix`) VALUES
(1, 'Test', 1, 'Aucune Description', 10);

-- --------------------------------------------------------

--
-- Structure de la table `produitsCommandes`
--

CREATE TABLE IF NOT EXISTS `produitsCommandes` (
  `idCommande` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  PRIMARY KEY (`idCommande`,`idProduit`),
  KEY `idProduit` (`idProduit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `produitsCommandes`
--


-- --------------------------------------------------------

--
-- Structure de la table `rangs`
--

CREATE TABLE IF NOT EXISTS `rangs` (
  `idRang` int(11) NOT NULL AUTO_INCREMENT,
  `label` text NOT NULL,
  `power` int(11) NOT NULL,
  PRIMARY KEY (`idRang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `rangs`
--


-- --------------------------------------------------------

--
-- Structure de la table `stocks`
--

CREATE TABLE IF NOT EXISTS `stocks` (
  `idProduit` int(11) NOT NULL,
  `stockRestant` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idProduit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `stocks`
--


-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `prenom` text NOT NULL,
  `nom` text NOT NULL,
  `rang` int(11) DEFAULT NULL,
  PRIMARY KEY (`idUtilisateur`),
  KEY `rang` (`rang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `utilisateurs`
--


-- --------------------------------------------------------

--
-- Structure de la table `visuelsProduits`
--

CREATE TABLE IF NOT EXISTS `visuelsProduits` (
  `idVisuel` int(11) NOT NULL AUTO_INCREMENT,
  `idProduit` int(11) NOT NULL,
  `nomImage` text NOT NULL,
  PRIMARY KEY (`idVisuel`),
  KEY `idProduit` (`idProduit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `visuelsProduits`
--


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateurs` (`idUtilisateur`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD CONSTRAINT `livraisons_ibfk_1` FOREIGN KEY (`modeLivraison`) REFERENCES `modesLivraisons` (`idModeLivraison`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`categorieProduit`) REFERENCES `categories` (`idCategorie`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `produitsCommandes`
--
ALTER TABLE `produitsCommandes`
  ADD CONSTRAINT `produitsCommandes_ibfk_2` FOREIGN KEY (`idProduit`) REFERENCES `produits` (`idProduit`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produitsCommandes_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commandes` (`idCommande`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`idProduit`) REFERENCES `produits` (`idProduit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `utilisateurs_ibfk_1` FOREIGN KEY (`rang`) REFERENCES `rangs` (`idRang`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `visuelsProduits`
--
ALTER TABLE `visuelsProduits`
  ADD CONSTRAINT `visuelsProduits_ibfk_1` FOREIGN KEY (`idProduit`) REFERENCES `produits` (`idProduit`) ON DELETE CASCADE ON UPDATE CASCADE;
