-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Jun-2024 às 02:11
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banco-bueiro`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_bueiro`
--

CREATE TABLE `tb_bueiro` (
  `id` int(50) NOT NULL,
  `numero_bueiro` int(50) NOT NULL,
  `rua` varchar(20) NOT NULL,
  `bairro` varchar(15) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `cidade` varchar(15) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `profundidade` double NOT NULL,
  `data_instalacao` datetime(6) NOT NULL,
  `id_sensor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `tb_bueiro`
--

INSERT INTO `tb_bueiro` (`id`, `numero_bueiro`, `rua`, `bairro`, `estado`, `cidade`, `latitude`, `longitude`, `profundidade`, `data_instalacao`, `id_sensor`) VALUES
(1, 1, 'rua china', 'cubatão', 'SP', 'Itapira', -22.432430921195067, -46.81233394903086, 2, '2024-05-01 00:00:00.000000', 1),
(2, 2, 'rua jose bonifacio', 'centro', 'SP', 'Itapira', -22.439346411657628, -46.825994878029384, 2, '2024-05-01 00:00:00.000000', 2),
(3, 3, 'rua minas gerais', 'cubatão', 'SP', 'Itapira', -22.43021531692228, -46.816220237384435, 2, '2024-05-01 00:00:00.000000', 3),
(4, 4, 'rua olavo job', 'istor luppi', 'sp', 'itapira', -22.456257298722974, -46.79164149404062, 3, '2024-06-13 00:00:00.000000', 4),
(5, 5, 'rua santa rita', 'Prados', 'SP', 'Itapira', -22.447489132558523, -46.80676051039583, 3, '2024-06-19 00:00:00.000000', 5),
(10, 10, 'Tereza Lera Paoletti', 'Jd. Bela Vista', 'SP', 'Itapira', -22.4311125, -46.8342055, 2, '2024-06-19 00:00:00.000000', 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_manutencao`
--

CREATE TABLE `tb_manutencao` (
  `id` int(50) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `date_time` datetime NOT NULL,
  `id_sensor` int(11) NOT NULL,
  `id_bueiro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `tb_manutencao`
--

INSERT INTO `tb_manutencao` (`id`, `descricao`, `date_time`, `id_sensor`, `id_bueiro`) VALUES
(10, 'Sensor 10', '2024-06-19 00:00:00', 10, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_medicao`
--

CREATE TABLE `tb_medicao` (
  `id` int(50) NOT NULL,
  `nivel_bueiro` double NOT NULL,
  `date_time` datetime NOT NULL,
  `id_sensor` int(11) NOT NULL,
  `id_bueiro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `tb_medicao`
--

INSERT INTO `tb_medicao` (`id`, `nivel_bueiro`, `date_time`, `id_sensor`, `id_bueiro`) VALUES
(10, 0, '2024-06-20 00:00:00', 10, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_sensor`
--

CREATE TABLE `tb_sensor` (
  `id` int(50) NOT NULL,
  `ativo` varchar(10) NOT NULL,
  `descricao` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Extraindo dados da tabela `tb_sensor`
--

INSERT INTO `tb_sensor` (`id`, `ativo`, `descricao`) VALUES
(1, 'S', 'Sensor 1'),
(2, 'S', 'Sensor 2'),
(3, 'S', 'Sensor 3'),
(4, 'Sim', 'Sensor 4'),
(5, '5', 'Sensor 5'),
(10, 'S', 'Sensor 10');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuarios`
--

CREATE TABLE `tb_usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_bueiro`
--
ALTER TABLE `tb_bueiro`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_manutencao`
--
ALTER TABLE `tb_manutencao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_medicao`
--
ALTER TABLE `tb_medicao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_sensor`
--
ALTER TABLE `tb_sensor`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_bueiro`
--
ALTER TABLE `tb_bueiro`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_manutencao`
--
ALTER TABLE `tb_manutencao`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_medicao`
--
ALTER TABLE `tb_medicao`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_sensor`
--
ALTER TABLE `tb_sensor`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
