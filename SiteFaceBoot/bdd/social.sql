-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 06 Février 2020 à 15:18
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `social`
--

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `idMedia` int(11) NOT NULL AUTO_INCREMENT,
  `typeMedia` varchar(50) NOT NULL,
  `nomMedia` varchar(100) NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modificationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
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
--

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`idPost`) REFERENCES `post` (`idPost`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
