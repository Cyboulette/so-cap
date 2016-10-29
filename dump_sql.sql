-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Sam 29 Octobre 2016 à 17:52
-- Version du serveur :  5.7.16-0ubuntu0.16.04.1
-- Version de PHP :  7.0.8-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `railot`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `idCategorie` int(11) NOT NULL,
  `label` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE `commandes` (
  `idCommande` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `dateCommande` datetime NOT NULL,
  `prixTotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

DROP TABLE IF EXISTS `livraisons`;
CREATE TABLE `livraisons` (
  `idLivraison` int(11) NOT NULL,
  `idCommande` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `etatCommande` text NOT NULL,
  `modeLivraison` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `modesLivraisons`
--

DROP TABLE IF EXISTS `modesLivraisons`;
CREATE TABLE `modesLivraisons` (
  `idModeLivraison` int(11) NOT NULL,
  `label` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE `produits` (
  `idProduit` int(11) NOT NULL,
  `label` text NOT NULL,
  `categorieProduit` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `prix` int(11) NOT NULL,
  `favorited` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `produitsCommandes`
--

DROP TABLE IF EXISTS `produitsCommandes`;
CREATE TABLE `produitsCommandes` (
  `idCommande` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rangs`
--

DROP TABLE IF EXISTS `rangs`;
CREATE TABLE `rangs` (
  `idRang` int(11) NOT NULL,
  `label` text NOT NULL,
  `power` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
  `idProduit` int(11) NOT NULL,
  `stockRestant` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
  `idUtilisateur` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `prenom` text NOT NULL,
  `nom` text NOT NULL,
  `rang` int(11) DEFAULT NULL,
  `nonce` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `visuelsProduits`
--

DROP TABLE IF EXISTS `visuelsProduits`;
CREATE TABLE `visuelsProduits` (
  `idVisuel` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `nomImage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`idCategorie`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`idCommande`),
  ADD KEY `idUtilisateur` (`idUtilisateur`);

--
-- Index pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD PRIMARY KEY (`idLivraison`),
  ADD KEY `modeLivraison` (`modeLivraison`);

--
-- Index pour la table `modesLivraisons`
--
ALTER TABLE `modesLivraisons`
  ADD PRIMARY KEY (`idModeLivraison`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`idProduit`),
  ADD KEY `categorieProduit` (`categorieProduit`);

--
-- Index pour la table `produitsCommandes`
--
ALTER TABLE `produitsCommandes`
  ADD PRIMARY KEY (`idCommande`,`idProduit`),
  ADD KEY `idProduit` (`idProduit`);

--
-- Index pour la table `rangs`
--
ALTER TABLE `rangs`
  ADD PRIMARY KEY (`idRang`);

--
-- Index pour la table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`idProduit`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`idUtilisateur`),
  ADD KEY `rang` (`rang`);

--
-- Index pour la table `visuelsProduits`
--
ALTER TABLE `visuelsProduits`
  ADD PRIMARY KEY (`idVisuel`),
  ADD KEY `idProduit` (`idProduit`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `idCategorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `idCommande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `livraisons`
--
ALTER TABLE `livraisons`
  MODIFY `idLivraison` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `modesLivraisons`
--
ALTER TABLE `modesLivraisons`
  MODIFY `idModeLivraison` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `idProduit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `rangs`
--
ALTER TABLE `rangs`
  MODIFY `idRang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `visuelsProduits`
--
ALTER TABLE `visuelsProduits`
  MODIFY `idVisuel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
  ADD CONSTRAINT `produitsCommandes_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commandes` (`idCommande`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produitsCommandes_ibfk_2` FOREIGN KEY (`idProduit`) REFERENCES `produits` (`idProduit`) ON DELETE CASCADE ON UPDATE CASCADE;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
