-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Machine: 127.0.0.1
-- Genereertijd: 26 jan 2016 om 16:02
-- Serverversie: 5.6.14
-- PHP-versie: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `bank`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `accountnumber` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `balance` float NOT NULL,
  `password` varchar(15) NOT NULL,
  `salt` varchar(15) NOT NULL,
  PRIMARY KEY (`accountnumber`),
  UNIQUE KEY `accountnumber` (`accountnumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `account`
--

INSERT INTO `account` (`accountnumber`, `name`, `balance`, `password`, `salt`) VALUES
('NL08RABO0784598758', 'bank', 4.2, 'bank', '7BHAORKDH123'),
('NL16RABO0846653421', 'wandelofniet', 5995.8, 'wandelOfNiet', 'A58JFK9874LAK');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(20) NOT NULL,
  `receiver` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  `amount` float NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Gegevens worden uitgevoerd voor tabel `payment`
--

INSERT INTO `payment` (`id`, `sender`, `receiver`, `date`, `amount`, `description`, `status`) VALUES
(15, 'NL16RABO0846653421', 'NL08RABO0784598758', '2016-01-26 15:44:04', 1, ' a', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
