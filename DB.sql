
-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 30, 2022 alle 22:23
-- Versione del server: 8.0.26
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_mywebs`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `EDC_artists`
--

CREATE TABLE IF NOT EXISTS `EDC_artists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `EDC_artists`
--

INSERT INTO `EDC_artists` (`id`, `name`) VALUES
(1, 'Bansky');

-- --------------------------------------------------------

--
-- Struttura della tabella `EDC_imgs`
--

CREATE TABLE IF NOT EXISTS `EDC_imgs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `year` year NOT NULL,
  `info` varchar(500) NOT NULL,
  `path` varchar(80) NOT NULL,
  `show_order` int NOT NULL,
  `id_artist` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `EDC_imgs`
--

INSERT INTO `EDC_imgs` (`id`, `name`, `year`, `info`, `path`, `show_order`, `id_artist`) VALUES
(1, 'Balloon Girl', 2002, 'Balloon Girl è una serie di stencil graffiti, iniziata a Londra nel 2002, raffigura una ragazza con la mano tesa verso un palloncino rosso a forma di cuore portato via dal vento.', 'https://mywebs.altervista.org/EDC/BALLOON_GIRL', 2, 1),
(2, 'Devolved Parliament', 2009, 'Il quadro rappresenta il parlamento inglese, utilizzando uno stile accademico. Al posto dei deputati, però, vengono raffigurate delle scimmie. Il taglio dato dall''opera, pertanto, risulta essere satirico', 'https://mywebs.altervista.org/EDC/DEVOLVED_PARLIAMENT', 0, 1),
(3, 'Slave Labour', 2012, 'L''opera raffigura un bambino inginocchiato davanti ad una macchina da cucire che assembla una pavese di bandiere raffiguranti l''Union Jack. L''opera è stata creata per protestare contro l''uso dei laboratori che sfruttano la manodopera minorile per la produzione di memorabilia per il Giubileo di diamante della regina Elisabetta II e delle Olimpiadi di Londra nel 2012', 'https://mywebs.altervista.org/EDC/SLAVE_LABOUR', 4, 1),
(4, 'Mobile Lovers', 2014, 'L''opera raffigura una coppia di innamorati che si abbraccia mentre entrambi controllano le ultime notifiche comparse sullo smartphone', 'https://mywebs.altervista.org/EDC/MOBILE_LOVERS', 3, 1),
(5, 'The Son Of A Migrant From Syria', 2015, 'L''opera raffigura il cofondatore ed ex CEO di Apple Steve Jobs, figlio di un siriano emigrato negli Stati Uniti, come un migrante in viaggio', 'https://mywebs.altervista.org/EDC/TSOAMFS', 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `EDC_vals`
--

CREATE TABLE IF NOT EXISTS `EDC_vals` (
  `id_img` int NOT NULL,
  `c_like` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `EDC_vals`
--

INSERT INTO `EDC_vals` (`id_img`, `c_like`) VALUES
(1, 4),
(2, 5),
(3, 2),
(4, 2),
(5, 3);

-- --------------------------------------------------------

