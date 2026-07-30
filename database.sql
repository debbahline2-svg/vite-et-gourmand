-- phpMyAdmin SQL Dump — version corrigée et nettoyée
-- Base de données : `vite_et_gourmand`
-- Corrections : suppression des colonnes orphelines (menu_id, note,
-- commentaire dans commandes), suppression du compte de test testfinal@fr,
-- suppression des commandes orphelines/de test, ajout de la contrainte FK
-- sur user_id (cf. 4.5 du dossier de projet)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `note` int(11) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `date_avis` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `nom_client` varchar(255) DEFAULT NULL,
  `email_client` varchar(255) DEFAULT NULL,
  `convives` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_evenement` date DEFAULT NULL,
  `message` text DEFAULT NULL,
  `nb_personnes` int(11) DEFAULT NULL,
  `total_prix` decimal(10,2) DEFAULT NULL,
  `message_special` text DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'En attente',
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `date_modification` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `distance_km` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Commandes orphelines/test retirées : 5, 14, 19, 20, 21, 22, 23
-- (user_id inexistant en base, données non représentatives)
INSERT INTO `commandes` (`id`, `nom_client`, `email_client`, `convives`, `id_menu`, `telephone`, `date_evenement`, `message`, `nb_personnes`, `total_prix`, `message_special`, `statut`, `date_commande`, `user_id`, `date_modification`, `distance_km`) VALUES
(24, 'Client', NULL, NULL, 2, NULL, '2026-05-20', NULL, 15, 1297.50, NULL, 'annulee', '2026-05-15 13:30:24', 5, '2026-05-20 16:54:23', 0.00),
(25, 'Client', NULL, NULL, 3, NULL, '2026-05-12', NULL, 11, 559.50, NULL, 'en préparation', '2026-05-15 13:30:57', 5, '2026-05-20 16:46:48', 0.00),
(26, 'Client', NULL, NULL, 1, NULL, '2026-05-29', NULL, 11, 840.00, NULL, 'en attente', '2026-05-17 13:27:45', 5, '2026-05-20 20:33:35', 0.00),
(27, NULL, NULL, NULL, 2, NULL, '2026-05-14', NULL, 5, 490.00, NULL, 'en attente', '2026-05-20 14:55:07', 5, '2026-05-20 20:33:49', 0.00),
(28, NULL, NULL, NULL, 2, NULL, '2026-05-14', NULL, 5, 490.00, NULL, 'en attente', '2026-05-20 14:55:27', 5, '2026-05-20 20:33:42', 0.00),
(29, NULL, NULL, NULL, 2, NULL, '2026-05-14', NULL, 5, 490.00, NULL, 'en attente', '2026-05-20 14:55:32', 5, '2026-05-20 20:33:52', 0.00),
(30, NULL, NULL, NULL, 2, NULL, '2026-05-14', NULL, 10, 870.00, NULL, 'accepté', '2026-05-20 15:00:07', 5, '2026-05-20 18:49:57', 0.00),
(31, NULL, NULL, NULL, 2, NULL, '2026-05-07', NULL, 10, 922.08, NULL, 'en attente', '2026-05-20 18:29:55', 5, '2026-05-20 20:33:25', 12.00);

-- --------------------------------------------------------

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `theme` varchar(100) DEFAULT NULL,
  `regime` varchar(100) DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `nb_pers_min` int(11) DEFAULT 1,
  `stock` int(11) DEFAULT 0,
  `conditions_speciales` text DEFAULT NULL,
  `entree_nom` varchar(255) DEFAULT NULL,
  `entree_img` varchar(255) DEFAULT NULL,
  `entree_allergenes` text DEFAULT NULL,
  `plat_nom` varchar(255) DEFAULT NULL,
  `plat_img` varchar(255) DEFAULT NULL,
  `plat_allergenes` text DEFAULT NULL,
  `dessert_nom` varchar(255) DEFAULT NULL,
  `dessert_img` varchar(255) DEFAULT NULL,
  `dessert_allergenes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `menus` (`id`, `titre`, `description`, `theme`, `regime`, `prix`, `nb_pers_min`, `stock`, `conditions_speciales`, `entree_nom`, `entree_img`, `entree_allergenes`, `plat_nom`, `plat_img`, `plat_allergenes`, `dessert_nom`, `dessert_img`, `dessert_allergenes`) VALUES
(1, 'Menu Mariage', 'Une formule de prestige pour votre union.', 'Évènement', 'Classique', 75.00, 10, 50, NULL, 'Duo Tartare Saumon', 'entree_mariage.png', 'Poisson, Lactose', 'Saint-Jacques Rôties', 'plat_mariage.png', 'Coquillages', 'Verrine Framboise', 'dessert_mariage.png', 'Fruits à coque'),
(2, 'Noël & Nouvel An', 'Le prestige des fêtes de fin d\'année.', 'Noël', 'Classique', 95.00, 5, 20, NULL, 'Foie Gras Maison', 'entree_fetes.png', 'Gluten', 'Canard aux Groseilles', 'plat_fetes.jpg', 'Aucun', 'Bûche Signature', 'dessert_fetes.png', 'Lactose, Œufs'),
(3, 'Menu Gastronomique', 'L\'excellence culinaire par Julie & José.', 'Classique', 'Végétarien', 55.00, 2, 30, NULL, 'Tartare de Saumon', 'entree_gastro.jpg', 'Poisson', 'Bœuf du Limousin', 'plat_gastro.jpg', 'Aucun', 'Dôme Caramel', 'dessert_gastro.png', 'Lactose');

-- --------------------------------------------------------

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `identifiant` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `gsm` varchar(20) NOT NULL,
  `adresse` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- testfinal@fr (id 8) supprimé
INSERT INTO `utilisateurs` (`id`, `identifiant`, `mot_de_passe`, `role`, `nom`, `prenom`, `gsm`, `adresse`, `is_active`) VALUES
(5, 'enen@test.fr', '$2y$10$qPm6xmxizZHsvcdqFVMJo.0LNZ6BHeT0vyzxlVUru6n1slyS0Yd3K', 'client', 'en', 'en', '0606060606', '23RUE TULIPE', 1),
(6, 'juliet@fr', '$2y$10$sm7vsKskuca6xlwSvEWOs.FC4OqM46oWkaz/nwQtwWjMHKtPxH1le', 'employe', 'traiteur', 'Julie', '0606060606', '41rue file', 1),
(7, 'klenkle@fr', '$2y$10$07otNErUZHk6.7kGXPIpDefILZYtpHWgpOR9rEuZtgbyaGuPUkGrK', 'admin', 'kll', 'klen', '0909090909', '32Rue lok', 1);

-- --------------------------------------------------------

ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_commande` (`id_commande`);

ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_identifiant` (`identifiant`);

ALTER TABLE `avis` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `commandes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
ALTER TABLE `menus` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `utilisateurs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id`);

ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;