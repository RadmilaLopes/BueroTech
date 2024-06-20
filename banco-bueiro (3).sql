-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 20-Jun-2024 às 04:02
-- Versão do servidor: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `banco-bueiro`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_bueiro`
--

CREATE TABLE IF NOT EXISTS `tb_bueiro` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `numero_bueiro` int(50) NOT NULL,
  `rua` varchar(20) NOT NULL,
  `bairro` varchar(15) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `cidade` varchar(15) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `profundidade` double NOT NULL,
  `data_instalacao` datetime(6) NOT NULL,
  `id_sensor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `tb_bueiro`
--

INSERT INTO `tb_bueiro` (`id`, `numero_bueiro`, `rua`, `bairro`, `estado`, `cidade`, `latitude`, `longitude`, `profundidade`, `data_instalacao`, `id_sensor`) VALUES
(1, 1, 'rua china', 'cubatão', 'SP', 'Itapira', -22.432430921195067, -46.81233394903086, 2, '2024-05-01 00:00:00.000000', 1),
(2, 2, 'rua jose bonifacio', 'centro', 'SP', 'Itapira', -22.439346411657628, -46.825994878029384, 2, '2024-05-01 00:00:00.000000', 2),
(3, 3, 'rua minas gerais', 'cubatão', 'SP', 'Itapira', -22.43021531692228, -46.816220237384435, 2, '2024-05-01 00:00:00.000000', 3),
(4, 4, 'rua olavo job', 'istor luppi', 'sp', 'itapira', -22.456257298722974, -46.79164149404062, 3, '2024-06-13 00:00:00.000000', 4),
(5, 5, 'rua santa rita', 'Prados', 'SP', 'Itapira', -22.447489132558523, -46.80676051039583, 3, '2024-06-19 00:00:00.000000', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_manutencao`
--

CREATE TABLE IF NOT EXISTS `tb_manutencao` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `date_time` datetime NOT NULL,
  `id_sensor` int(11) NOT NULL,
  `id_bueiro` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_medicao`
--

CREATE TABLE IF NOT EXISTS `tb_medicao` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `nivel_bueiro` double NOT NULL,
  `date_time` datetime NOT NULL,
  `id_sensor` int(11) NOT NULL,
  `id_bueiro` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `tb_medicao`
--

INSERT INTO `tb_medicao` (`id`, `nivel_bueiro`, `date_time`, `id_sensor`, `id_bueiro`) VALUES
(10, 1.1, '2024-06-18 00:00:00', 1, 1),
(11, 1, '2024-06-18 00:00:00', 2, 2),
(13, 2, '2024-06-18 00:00:00', 3, 3),
(14, 1.3, '2024-06-19 00:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_sensor`
--

CREATE TABLE IF NOT EXISTS `tb_sensor` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `ativo` varchar(10) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `tb_sensor`
--

INSERT INTO `tb_sensor` (`id`, `ativo`, `descricao`) VALUES
(1, 'S', 'Sensor 1'),
(2, 'S', 'Sensor 2'),
(3, 'S', 'Sensor 3'),
(4, 'Sim', 'Sensor 4'),
(5, '5', 'Sensor 5');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
