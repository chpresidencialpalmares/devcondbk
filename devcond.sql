-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20/06/2023 às 14:18
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `devcond`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `areadisableddays`
--

CREATE TABLE `areadisableddays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_area` int(11) NOT NULL,
  `day` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `areadisableddays`
--

INSERT INTO `areadisableddays` (`id`, `id_area`, `day`) VALUES
(1, 2, '2023-05-24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `areas`
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allowed` int(11) NOT NULL DEFAULT 1,
  `title` varchar(255) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `days` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `areas`
--

INSERT INTO `areas` (`id`, `allowed`, `title`, `cover`, `days`, `start_time`, `end_time`) VALUES
(1, 1, 'Academia', 'gym.jpg', '1,2,4,5', '06:00:00', '22:00:00'),
(2, 1, 'Piscina', 'pool.jpg', '1,2,3,4,5', '07:00:00', '23:00:00'),
(3, 1, 'Churrasqueira', 'barbecue.jpg', '4,5,6', '09:00:00', '23:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `billets`
--

CREATE TABLE `billets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_unit` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `fileurl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `billets`
--

INSERT INTO `billets` (`id`, `id_unit`, `title`, `fileurl`) VALUES
(1, 1, '1_Dez2020', 'dez2020.pdf');

-- --------------------------------------------------------

--
-- Estrutura para tabela `docs`
--

CREATE TABLE `docs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `fileurl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `docs`
--

INSERT INTO `docs` (`id`, `title`, `fileurl`) VALUES
(1, 'Regras Condo', 'regras.pdf');

-- --------------------------------------------------------

--
-- Estrutura para tabela `foundandlost`
--

CREATE TABLE `foundandlost` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'LOST',
  `photo` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `where` varchar(255) NOT NULL,
  `datecreated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `foundandlost`
--

INSERT INTO `foundandlost` (`id`, `status`, `photo`, `description`, `where`, `datecreated`) VALUES
(1, 'LOST', 'alguma.jpg', 'carteira azul com dinhieor', 'no patio', '2023-06-16'),
(2, 'RECOVERED', 'alguma2.jpg', 'pente verde', 'parquinho', '2023-06-16'),
(3, 'LOST', 'Knd9ps57LfBqvLOh7dkuvNQTT8wyi61O8YyD1QYg.jpg', 'foto de alguem', 'no meiod a rua', '2023-06-16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2023_06_16_122302_createalltables', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservations`
--

CREATE TABLE `reservations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_unit` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `reservation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `reservations`
--

INSERT INTO `reservations` (`id`, `id_unit`, `id_area`, `reservation_date`) VALUES
(1, 2, 2, '2020-12-23 09:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `unitpeoples`
--

CREATE TABLE `unitpeoples` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_unit` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `unitpeoples`
--

INSERT INTO `unitpeoples` (`id`, `id_unit`, `name`, `birthdate`) VALUES
(1, 2, 'fulano ', '2023-06-05'),
(2, 2, 'ciclano ', '2020-02-05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `unitpets`
--

CREATE TABLE `unitpets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_unit` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `race` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `unitpets`
--

INSERT INTO `unitpets` (`id`, `id_unit`, `name`, `race`) VALUES
(1, 2, 'Zaue', 'Vira lata');

-- --------------------------------------------------------

--
-- Estrutura para tabela `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `units`
--

INSERT INTO `units` (`id`, `name`, `id_owner`) VALUES
(1, 'APT 100', 1),
(2, 'APT 101', 1),
(3, 'APT 200', 0),
(4, 'APT 201', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `unitvehicles`
--

CREATE TABLE `unitvehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_unit` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `plate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `unitvehicles`
--

INSERT INTO `unitvehicles` (`id`, `id_unit`, `title`, `color`, `plate`) VALUES
(2, 2, 'Celta', 'preto', 'aAA444');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cpf` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `cpf`, `password`) VALUES
(1, 'Alexandre', 'informatica@chpresidencialpalmares.com.br', '12345678911', '$2y$10$G.ge/ZJA55V9BYfe6FlKyuk91ysIxHaaWPLbvy.PXTwQwkKXVUYgK');

-- --------------------------------------------------------

--
-- Estrutura para tabela `walllikes`
--

CREATE TABLE `walllikes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_wall` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `walllikes`
--

INSERT INTO `walllikes` (`id`, `id_wall`, `id_user`) VALUES
(2, 1, 1),
(3, 2, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `walls`
--

CREATE TABLE `walls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` varchar(255) NOT NULL,
  `datecreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `walls`
--

INSERT INTO `walls` (`id`, `title`, `body`, `datecreated`) VALUES
(1, 'Titulo de aviso de teste', 'Lorem ipsim fgisifdssps', '2020-12-20 15:00:00'),
(2, 'Alerta geral para toddods', 'balallalalalalal cuida blabla', '2020-12-20 18:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `warnings`
--

CREATE TABLE `warnings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_unit` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'IN_REVIEW',
  `datecreated` date NOT NULL,
  `photos` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `warnings`
--

INSERT INTO `warnings` (`id`, `id_unit`, `title`, `status`, `datecreated`, `photos`) VALUES
(1, 2, 'Vizinho eh chato', 'IN_REVIEW', '2023-06-16', 'foto1.jpg,foto2.jpg'),
(2, 2, 'Outra sitacao', 'IN_REVIEW', '2023-06-16', 'foto3.jpg,foto4.jpg'),
(3, 2, 'Vizinho chato via API', 'IN_REVIEW', '2023-06-16', ''),
(4, 2, 'Vizinho feio com foto', 'IN_REVIEW', '2023-06-16', 'ibuMZczfKmDjEeo6tJLylLgfTbakqTSs2evMqilv.jpg,ibuMZczfKmDjEeo6tJLylLgfTbakqTSs2evMqilv.jpg');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `areadisableddays`
--
ALTER TABLE `areadisableddays`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `billets`
--
ALTER TABLE `billets`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `docs`
--
ALTER TABLE `docs`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `foundandlost`
--
ALTER TABLE `foundandlost`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Índices de tabela `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `unitpeoples`
--
ALTER TABLE `unitpeoples`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `unitpets`
--
ALTER TABLE `unitpets`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `unitvehicles`
--
ALTER TABLE `unitvehicles`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_cpf_unique` (`cpf`);

--
-- Índices de tabela `walllikes`
--
ALTER TABLE `walllikes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `walls`
--
ALTER TABLE `walls`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `warnings`
--
ALTER TABLE `warnings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `areadisableddays`
--
ALTER TABLE `areadisableddays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `billets`
--
ALTER TABLE `billets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `docs`
--
ALTER TABLE `docs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `foundandlost`
--
ALTER TABLE `foundandlost`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `unitpeoples`
--
ALTER TABLE `unitpeoples`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `unitpets`
--
ALTER TABLE `unitpets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `unitvehicles`
--
ALTER TABLE `unitvehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `walllikes`
--
ALTER TABLE `walllikes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `walls`
--
ALTER TABLE `walls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `warnings`
--
ALTER TABLE `warnings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
