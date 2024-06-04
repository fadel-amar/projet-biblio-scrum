-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 04 juin 2024 à 22:15
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_biblio`
--

-- --------------------------------------------------------

--
-- Structure de la table `adherent`
--

CREATE TABLE `adherent` (
  `id` int(11) NOT NULL,
  `numero_adherent` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `date_adhesion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `adherent`
--

INSERT INTO `adherent` (`id`, `numero_adherent`, `prenom`, `nom`, `email`, `date_adhesion`) VALUES
(1, 'AD-099216', 'Kylian', 'Mbappe', 'km@test.fr', '2024-06-03'),
(2, 'AD-688398', 'Jean', 'Luc', 'jl@test.fr', '2024-06-03'),
(3, 'AD-011859', 'Sandra', 'Nicouverture', 'sn@gmail.com', '2024-06-03'),
(4, 'AD-256102', 'Louis', 'Lucas', 'll@test.fr', '2024-06-03');

-- --------------------------------------------------------

--
-- Structure de la table `bluray`
--

CREATE TABLE `bluray` (
  `id` int(11) NOT NULL,
  `realisateur` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `duree` int(11) NOT NULL,
  `anneeSortie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `bluray`
--

INSERT INTO `bluray` (`id`, `realisateur`, `duree`, `anneeSortie`) VALUES
(5, 'Jilles', 203, 2023),
(6, 'Neil', 157, 2024);

-- --------------------------------------------------------

--
-- Structure de la table `emprunt`
--

CREATE TABLE `emprunt` (
  `id` int(11) NOT NULL,
  `id_media` int(11) DEFAULT NULL,
  `id_adherent` int(11) DEFAULT NULL,
  `numeroEmprunt` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `dateEmprunt` datetime NOT NULL,
  `dateRetourEstime` datetime NOT NULL,
  `dateRetour` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `emprunt`
--

INSERT INTO `emprunt` (`id`, `id_media`, `id_adherent`, `numeroEmprunt`, `dateEmprunt`, `dateRetourEstime`, `dateRetour`) VALUES
(1, 1, 3, 'EM-000000001', '2024-06-03 23:15:20', '2024-06-24 23:15:20', '2024-06-03 23:22:50'),
(2, 3, 1, 'EM-000000002', '2024-06-03 23:16:39', '2024-06-13 23:16:39', '2024-06-03 23:22:55'),
(3, 2, 3, 'EM-000000003', '2024-06-03 23:21:53', '2024-06-24 23:21:53', '2024-06-03 23:23:01'),
(4, 1, 4, 'EM-000000004', '2024-06-03 23:25:31', '2024-06-24 23:25:31', '2024-06-03 23:32:04'),
(5, 2, 2, 'EM-000000005', '2024-06-03 23:25:39', '2024-06-24 23:25:39', '2024-06-03 23:32:11'),
(6, 6, 1, 'EM-000000006', '2024-06-03 23:33:11', '2024-06-18 23:33:11', '2024-06-03 23:36:56'),
(7, 4, 3, 'EM-000000007', '2024-06-03 23:34:36', '2024-06-13 23:34:36', '2024-06-03 23:37:06'),
(8, 3, 2, 'EM-000000008', '2024-06-03 23:35:50', '2024-06-13 23:35:50', '2024-06-03 23:37:11'),
(9, 2, 2, 'EM-000000009', '2024-06-03 23:36:14', '2024-06-24 23:36:14', '2024-06-03 23:37:16'),
(10, 1, 1, 'EM-000000010', '2024-06-03 23:38:03', '2024-06-24 23:38:03', '2024-06-03 23:38:31'),
(11, 6, 1, 'EM-000000011', '2024-06-03 23:40:06', '2024-06-18 23:40:06', '2024-06-03 23:41:39'),
(12, 1, 4, 'EM-000000012', '2024-06-03 23:42:36', '2024-06-24 23:42:36', '2024-06-03 23:42:51');

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

CREATE TABLE `livre` (
  `id` int(11) NOT NULL,
  `isbn` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `auteur` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nbPages` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `livre`
--

INSERT INTO `livre` (`id`, `isbn`, `auteur`, `nbPages`) VALUES
(1, '0-1368-3891-X', 'Zidane', 156),
(2, '0-3746-8587-8', 'Kendrick', 456);

-- --------------------------------------------------------

--
-- Structure de la table `magazine`
--

CREATE TABLE `magazine` (
  `id` int(11) NOT NULL,
  `numero` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `datePublication` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `magazine`
--

INSERT INTO `magazine` (`id`, `numero`, `datePublication`) VALUES
(3, '1563', '03/05/2022'),
(4, '8941', '30/05/2024');

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `dureeEmprunt` int(11) NOT NULL,
  `titre` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id`, `dureeEmprunt`, `titre`, `status`, `dateCreation`, `type`) VALUES
(1, 21, 'Comment jouer au fooùt', 'Disponible', '2024-06-03 23:06:25', 'livre'),
(2, 21, 'Roi soleil', 'Disponible', '2024-06-03 23:06:54', 'livre'),
(3, 10, 'Top 10 ballons d\'or', 'Disponible', '2024-06-03 23:07:23', 'magazine'),
(4, 10, 'Nouvelle mode 2024', 'Disponible', '2024-06-03 23:08:47', 'magazine'),
(5, 15, 'Maco', 'Nouveau', '2024-06-03 23:10:29', 'BluRay'),
(6, 15, '1 jour', 'Disponible', '2024-06-03 23:11:35', 'BluRay');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adherent`
--
ALTER TABLE `adherent`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `bluray`
--
ALTER TABLE `bluray`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `emprunt`
--
ALTER TABLE `emprunt`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F9FD484B5809BCF2` (`numeroEmprunt`),
  ADD KEY `IDX_F9FD484B84A9E03C` (`id_media`),
  ADD KEY `IDX_F9FD484BC0081CF5` (`id_adherent`);

--
-- Index pour la table `livre`
--
ALTER TABLE `livre`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `magazine`
--
ALTER TABLE `magazine`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adherent`
--
ALTER TABLE `adherent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `emprunt`
--
ALTER TABLE `emprunt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bluray`
--
ALTER TABLE `bluray`
  ADD CONSTRAINT `FK_C976A986BF396750` FOREIGN KEY (`id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `emprunt`
--
ALTER TABLE `emprunt`
  ADD CONSTRAINT `FK_F9FD484B84A9E03C` FOREIGN KEY (`id_media`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `FK_F9FD484BC0081CF5` FOREIGN KEY (`id_adherent`) REFERENCES `adherent` (`id`);

--
-- Contraintes pour la table `livre`
--
ALTER TABLE `livre`
  ADD CONSTRAINT `FK_6DA2609DBF396750` FOREIGN KEY (`id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `magazine`
--
ALTER TABLE `magazine`
  ADD CONSTRAINT `FK_CEFA4DB2BF396750` FOREIGN KEY (`id`) REFERENCES `media` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
