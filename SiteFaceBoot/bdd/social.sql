-- phpMyAdmin SQL Dump
<<<<<<< HEAD
<<<<<<< HEAD
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 20 fév. 2020 à 22:54
-- Version du serveur :  8.0.19
-- Version de PHP : 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
=======
=======
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 06 Février 2020 à 15:18
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
<<<<<<< HEAD
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
=======
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
<<<<<<< HEAD
<<<<<<< HEAD
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `social`
=======
=======
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `social`
<<<<<<< HEAD
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
=======
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
--

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

<<<<<<< HEAD
<<<<<<< HEAD
CREATE TABLE `media` (
  `idMedia` int NOT NULL,
=======
CREATE TABLE IF NOT EXISTS `media` (
  `idMedia` int(11) NOT NULL AUTO_INCREMENT,
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
=======
CREATE TABLE IF NOT EXISTS `media` (
  `idMedia` int(11) NOT NULL AUTO_INCREMENT,
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
  `typeMedia` varchar(50) NOT NULL,
  `nomMedia` varchar(100) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modificationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
<<<<<<< HEAD
<<<<<<< HEAD
  `idPost` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `idPost` int NOT NULL,
  `commentaire` varchar(200) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modificationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`idMedia`),
  ADD KEY `idPost` (`idPost`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`idPost`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `idMedia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `idPost` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
=======
=======
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
  `idPost` int(11) NOT NULL,
  PRIMARY KEY (`idMedia`),
  KEY `idPost` (`idPost`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `media`
--

INSERT INTO `media` (`idMedia`, `typeMedia`, `nomMedia`, `creationDate`, `modificationDate`, `idPost`) VALUES
(9, '.png', 'upload/5e3c1f25bea9c_formesWeb.png', '2020-02-06 14:13:57', '0000-00-00 00:00:00', 9),
(10, '.jpg', 'upload/5e3c1f25bff00_tank.jpg', '2020-02-06 14:13:57', '0000-00-00 00:00:00', 9);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `idPost` int(11) NOT NULL AUTO_INCREMENT,
  `commentaire` varchar(200) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modificationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`idPost`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `post`
--

INSERT INTO `post` (`idPost`, `commentaire`, `creationDate`, `modificationDate`) VALUES
(9, 'rtfurtj', '2020-02-06 14:13:57', '0000-00-00 00:00:00');

--
-- Contraintes pour les tables exportées
<<<<<<< HEAD
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
=======
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
--

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`);
<<<<<<< HEAD
<<<<<<< HEAD
COMMIT;
=======
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159
=======
>>>>>>> 6bed5998f4bb095c62030646e03188911a007159

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
