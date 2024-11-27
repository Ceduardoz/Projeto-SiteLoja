
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `tipo_ingresso` longblob NOT NULL DEFAULT '\'\'',
  `pagamento` enum('Cartão','Pix','Boleto') NOT NULL,
  `data_compra` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `compras` (`id`, `evento_id`, `nome`, `email`, `quantidade`, `tipo_ingresso`, `pagamento`, `data_compra`) VALUES
(3, 4, 'Carlos Eduardo Barbosa Alves', 'ceduardoz957@gmail.com', 5, 0x7069737461, 'Pix', '2024-11-27 13:55:03'),
(4, 4, 'Carlos Eduardo Barbosa Alves', 'ceduardoz957@gmail.com', 5, 0x7069737461, 'Pix', '2024-11-27 13:55:53'),
(5, 4, 'Chico Butico', 'chico123@gmail.com', 1, 0x7069737461, 'Cartão', '2024-11-27 15:16:23'),
(6, 4, 'Chico Butico', 'chico123@gmail.com', 5, 0x7069737461, 'Cartão', '2024-11-27 15:20:44');

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_evento` datetime NOT NULL,
  `local` varchar(255) NOT NULL,
  `ingressos_disponiveis` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `eventos` (`id`, `nome`, `descricao`, `data_evento`, `local`, `ingressos_disponiveis`, `preco`) VALUES
(4, 'CAC do Rangel', 'Um show imperdível com os maiores sucessos.', '2024-11-25 00:00:00', 'São Paulo', 20, 100.00),
(5, 'Pastoril Profano', 'Pastoril Profano o melhor que você ja viu.', '2025-05-03 00:00:00', 'João Pessoa', 10, 200.00),
(6, 'Nostalgia 2000', 'Nostagia 2000 pra ficar na paz.', '2024-12-10 00:00:00', 'São Paulo', 10, 300.00);

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` (`id`, `nome`, `cpf`, `telefone`, `endereco`, `email`, `senha`, `data_criacao`, `is_admin`) VALUES
(14, 'Administrador', '00000000000', '000000000', 'Endereço do Admin', 'admin@gmail.com', 'admin1\r\n', '2024-11-27 13:29:35', 1),
(15, 'Carlos Eduardo Barbosa Alves', '10431997489', '81992302347', 'Rua Almirante Barroso, 301', 'ceduardoz957@gmail.com', '$2y$10$UzlhH7ETKisV0YhuZbW.tuhi/E7zU58McfMYthy7ha4BB6u9R3Hza', '2024-11-27 13:31:01', 0),
(16, 'Chico Butico ', '98263461243', '81929742743', 'rua da bala ', 'chico123@gmail.com', '$2y$10$oU45piQTEUqZ9q7bc0rl3uQPSEWbOEaDegRdOlo4MbjMFtSlAMQsq', '2024-11-27 13:57:57', 0);

ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evento_id` (`evento_id`);


ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE;
COMMIT;
