-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 28 avr. 2026 à 18:15
-- Version du serveur : 8.0.45
-- Version de PHP : 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_de_stock_test`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `art_id` int NOT NULL,
  `art_libelle` varchar(50) NOT NULL,
  `art_largeur` int DEFAULT NULL,
  `art_longueur` int DEFAULT NULL,
  `art_couleur` int DEFAULT NULL,
  `art_matiere` int DEFAULT NULL,
  `art_stock_total_mini` int NOT NULL,
  `art_stock_mini` int NOT NULL,
  `art_temoin_de_suppression` tinyint(1) DEFAULT '1',
  `art_date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `art_date_mise_a_jour` timestamp NULL DEFAULT NULL,
  `art_date_suppression` timestamp NULL DEFAULT NULL,
  `art_utilisateur` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `couleurs`
--

CREATE TABLE `couleurs` (
  `cou_id` int NOT NULL,
  `cou_libelle` varchar(50) NOT NULL,
  `cou_temoin_de_suppression` tinyint(1) DEFAULT '1',
  `cou_date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cou_date_mise_a_jour` timestamp NULL DEFAULT NULL,
  `cou_date_suppression` timestamp NULL DEFAULT NULL,
  `cou_utilisateur` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dimensions`
--

CREATE TABLE `dimensions` (
  `dim_id` int NOT NULL,
  `dim_libelle` int NOT NULL,
  `dim_temoin_de_suppression` tinyint(1) DEFAULT '1',
  `dim_date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `dim_date_mise_a_jour` timestamp NULL DEFAULT NULL,
  `dim_date_suppression` timestamp NULL DEFAULT NULL,
  `dim_utilisateur` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etapes`
--

CREATE TABLE `etapes` (
  `etp_id` int NOT NULL,
  `etp_libelle` varchar(50) NOT NULL,
  `etp_temoin_suppression` tinyint(1) DEFAULT '1',
  `etp_date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `etp_date_mise_a_jour` timestamp NULL DEFAULT NULL,
  `etp_date_supression` timestamp NULL DEFAULT NULL,
  `etp_utilisateur` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `etapes`
--

INSERT INTO `etapes` (`etp_id`, `etp_libelle`, `etp_temoin_suppression`, `etp_date_creation`, `etp_date_mise_a_jour`, `etp_date_supression`, `etp_utilisateur`) VALUES
(1, 'en cours', 1, '2026-04-28 15:50:41', NULL, NULL, NULL),
(2, 'en stock', 1, '2026-04-28 15:50:41', NULL, NULL, NULL),
(3, 'en defaut', 1, '2026-04-28 15:50:41', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `historisation`
--

CREATE TABLE `historisation` (
  `hst_id` int NOT NULL,
  `hst_numero_piece` int NOT NULL,
  `hst_numero_article` int NOT NULL,
  `hst_etape_precedente` int DEFAULT NULL,
  `hst_etape_en_cours` int DEFAULT NULL,
  `hst_date_etape` datetime DEFAULT NULL,
  `hst_statut_piece` varchar(50) NOT NULL,
  `hst_temoin_suppression` tinyint(1) DEFAULT NULL,
  `hst_type_anomalie` int DEFAULT NULL,
  `hst_texte_anomalie` text,
  `hst_date_creation` timestamp NULL DEFAULT NULL,
  `hst_date_suppression` timestamp NULL DEFAULT NULL,
  `hst_utilisateur` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lots`
--

CREATE TABLE `lots` (
  `lot_id` int NOT NULL,
  `lot_etape_destination` int NOT NULL,
  `lot_etape_libelle` varchar(50) NOT NULL,
  `lot_statut` enum('actif','inactif') DEFAULT 'actif',
  `lot_date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lot_date_mise_a_jour` timestamp NULL DEFAULT NULL,
  `lot_utilisateur` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lot_pieces`
--

CREATE TABLE `lot_pieces` (
  `ltp_id` int NOT NULL,
  `ltp_lot_id` int NOT NULL,
  `ltp_pce_id` int NOT NULL,
  `ltp_statut` enum('dans_lot','traite') DEFAULT 'dans_lot',
  `ltp_date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ltp_date_traitement` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `mat_id` int NOT NULL,
  `mat_libelle` varchar(50) NOT NULL,
  `mat_temoin_de_suppression` tinyint(1) DEFAULT '1',
  `mat_date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `mat_date_mise_a_jour` datetime DEFAULT NULL,
  `mat_date_suppression` datetime DEFAULT NULL,
  `mat_utilisateur` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pieces_de_linge`
--

CREATE TABLE `pieces_de_linge` (
  `pce_id` int NOT NULL,
  `pce_numero_article` int DEFAULT NULL,
  `pce_etape_en_cours` int DEFAULT NULL,
  `pce_date_etape` datetime DEFAULT NULL,
  `pce_etape_precedente` int DEFAULT NULL,
  `pce_type_anomalie` int DEFAULT NULL,
  `pce_texte_anomalie` text,
  `pce_statut` enum('actif','inactif') DEFAULT 'actif',
  `pce_temoin_de_suppression` tinyint(1) DEFAULT '1',
  `pce_date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pce_date_mise_a_jour` timestamp NULL DEFAULT NULL,
  `pce_date_suppression` timestamp NULL DEFAULT NULL,
  `pce_utilisateur` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `types_anomalie`
--

CREATE TABLE `types_anomalie` (
  `tano_id` int NOT NULL,
  `tano_libelle` varchar(50) NOT NULL,
  `tano_temoin_suppression` tinyint(1) DEFAULT '1',
  `tano_date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tano_date_mise_a_jour` timestamp NULL DEFAULT NULL,
  `tano_date_supression` timestamp NULL DEFAULT NULL,
  `tano_utilisateur` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`art_id`),
  ADD KEY `art_largeur` (`art_largeur`),
  ADD KEY `art_longueur` (`art_longueur`),
  ADD KEY `art_couleur` (`art_couleur`),
  ADD KEY `art_matiere` (`art_matiere`);

--
-- Index pour la table `couleurs`
--
ALTER TABLE `couleurs`
  ADD PRIMARY KEY (`cou_id`);

--
-- Index pour la table `dimensions`
--
ALTER TABLE `dimensions`
  ADD PRIMARY KEY (`dim_id`);

--
-- Index pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD PRIMARY KEY (`etp_id`);

--
-- Index pour la table `historisation`
--
ALTER TABLE `historisation`
  ADD PRIMARY KEY (`hst_id`),
  ADD KEY `hst_numero_piece` (`hst_numero_piece`),
  ADD KEY `hst_numero_article` (`hst_numero_article`),
  ADD KEY `hst_etape_precedente` (`hst_etape_precedente`),
  ADD KEY `hst_etape_en_cours` (`hst_etape_en_cours`),
  ADD KEY `hst_type_anomalie` (`hst_type_anomalie`);

--
-- Index pour la table `lots`
--
ALTER TABLE `lots`
  ADD PRIMARY KEY (`lot_id`),
  ADD KEY `lot_etape_destination` (`lot_etape_destination`);

--
-- Index pour la table `lot_pieces`
--
ALTER TABLE `lot_pieces`
  ADD PRIMARY KEY (`ltp_id`),
  ADD KEY `ltp_lot_id` (`ltp_lot_id`),
  ADD KEY `ltp_pce_id` (`ltp_pce_id`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`mat_id`);

--
-- Index pour la table `pieces_de_linge`
--
ALTER TABLE `pieces_de_linge`
  ADD PRIMARY KEY (`pce_id`),
  ADD KEY `pce_numero_article` (`pce_numero_article`),
  ADD KEY `pce_etape_en_cours` (`pce_etape_en_cours`),
  ADD KEY `pce_etape_precedente` (`pce_etape_precedente`),
  ADD KEY `pce_type_anomalie` (`pce_type_anomalie`);

--
-- Index pour la table `types_anomalie`
--
ALTER TABLE `types_anomalie`
  ADD PRIMARY KEY (`tano_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `art_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `couleurs`
--
ALTER TABLE `couleurs`
  MODIFY `cou_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dimensions`
--
ALTER TABLE `dimensions`
  MODIFY `dim_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etapes`
--
ALTER TABLE `etapes`
  MODIFY `etp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `historisation`
--
ALTER TABLE `historisation`
  MODIFY `hst_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lots`
--
ALTER TABLE `lots`
  MODIFY `lot_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lot_pieces`
--
ALTER TABLE `lot_pieces`
  MODIFY `ltp_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `mat_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pieces_de_linge`
--
ALTER TABLE `pieces_de_linge`
  MODIFY `pce_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `types_anomalie`
--
ALTER TABLE `types_anomalie`
  MODIFY `tano_id` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`art_largeur`) REFERENCES `dimensions` (`dim_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`art_longueur`) REFERENCES `dimensions` (`dim_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `articles_ibfk_3` FOREIGN KEY (`art_couleur`) REFERENCES `couleurs` (`cou_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `articles_ibfk_4` FOREIGN KEY (`art_matiere`) REFERENCES `matieres` (`mat_id`) ON DELETE RESTRICT;

--
-- Contraintes pour la table `historisation`
--
ALTER TABLE `historisation`
  ADD CONSTRAINT `historisation_ibfk_1` FOREIGN KEY (`hst_numero_piece`) REFERENCES `pieces_de_linge` (`pce_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `historisation_ibfk_2` FOREIGN KEY (`hst_numero_article`) REFERENCES `articles` (`art_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `historisation_ibfk_3` FOREIGN KEY (`hst_etape_precedente`) REFERENCES `etapes` (`etp_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `historisation_ibfk_4` FOREIGN KEY (`hst_etape_en_cours`) REFERENCES `etapes` (`etp_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `historisation_ibfk_5` FOREIGN KEY (`hst_type_anomalie`) REFERENCES `types_anomalie` (`tano_id`) ON DELETE RESTRICT;

--
-- Contraintes pour la table `lots`
--
ALTER TABLE `lots`
  ADD CONSTRAINT `lots_ibfk_1` FOREIGN KEY (`lot_etape_destination`) REFERENCES `etapes` (`etp_id`) ON DELETE RESTRICT;

--
-- Contraintes pour la table `lot_pieces`
--
ALTER TABLE `lot_pieces`
  ADD CONSTRAINT `lot_pieces_ibfk_1` FOREIGN KEY (`ltp_lot_id`) REFERENCES `lots` (`lot_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lot_pieces_ibfk_2` FOREIGN KEY (`ltp_pce_id`) REFERENCES `pieces_de_linge` (`pce_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `pieces_de_linge`
--
ALTER TABLE `pieces_de_linge`
  ADD CONSTRAINT `pieces_de_linge_ibfk_1` FOREIGN KEY (`pce_numero_article`) REFERENCES `articles` (`art_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `pieces_de_linge_ibfk_2` FOREIGN KEY (`pce_etape_en_cours`) REFERENCES `etapes` (`etp_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `pieces_de_linge_ibfk_3` FOREIGN KEY (`pce_etape_precedente`) REFERENCES `etapes` (`etp_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `pieces_de_linge_ibfk_4` FOREIGN KEY (`pce_type_anomalie`) REFERENCES `types_anomalie` (`tano_id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
