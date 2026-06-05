-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 05, 2026 at 05:35 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdmooc`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrateur`
--

CREATE TABLE `administrateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('SUPER ADMIN','ADMIN','GESTIONNAIRE') NOT NULL DEFAULT 'ADMIN',
  `login_attempts` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrateur`
--

INSERT INTO `administrateur` (`id`, `nom`, `prenom`, `email`, `password`, `role`, `login_attempts`) VALUES
(1, 'HOUNKPE', 'Rodrigue', 'admin@e4afrika.com', '$2y$10$V1hLmKg41ARZ/z7ysCi6ZO3Ety0gy09BYJDFry7QTr8LMzTodg4j.', 'SUPER ADMIN', 0);

-- --------------------------------------------------------

--
-- Table structure for table `apprenant`
--

CREATE TABLE `apprenant` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dateinscription` date NOT NULL,
  `statut` enum('ACTIF','SUSPENDU','TERMINE','ABANDON') NOT NULL DEFAULT 'ACTIF',
  `codopt` varchar(10) NOT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apprenant`
--

INSERT INTO `apprenant` (`id`, `nom`, `prenom`, `email`, `password`, `dateinscription`, `statut`, `codopt`, `login_attempts`, `locked_until`) VALUES
(1, 'AGOSSOU', 'Fidèle Joëlle', 'fidele.agossou@email.com', '$2y$10$Y8/znqg.1x7oMpD9rgD3OedqDfimzMMuJor5dH3Mf0Hfc48RZ.71C', '2024-09-05', 'ACTIF', 'OPT01', 0, NULL),
(2, 'BOCO', 'Gervais Marius', 'gervais.boco@email.com', '$2y$10$Y8/znqg.1x7oMpD9rgD3OedqDfimzMMuJor5dH3Mf0Hfc48RZ.71C', '2024-09-07', 'ACTIF', 'OPT01', 0, NULL),
(3, 'AZONDEKON', 'Mariette Cécile', 'mariette.azondekon@email.com', '$2y$10$Y8/znqg.1x7oMpD9rgD3OedqDfimzMMuJor5dH3Mf0Hfc48RZ.71C', '2024-09-10', 'ACTIF', 'OPT02', 0, NULL),
(4, 'GBENOU', 'Didier Ulrich', 'didier.gbenou@email.com', '$2y$10$Y8/znqg.1x7oMpD9rgD3OedqDfimzMMuJor5dH3Mf0Hfc48RZ.71C', '2024-10-01', 'ACTIF', 'OPT03', 0, NULL),
(5, 'KAKPO', 'Sylvie Nadège', 'sylvie.kakpo@email.com', '$2y$10$Y8/znqg.1x7oMpD9rgD3OedqDfimzMMuJor5dH3Mf0Hfc48RZ.71C', '2024-10-05', 'ACTIF', 'OPT04', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `codcat` varchar(10) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`codcat`, `libelle`) VALUES
('CAT01', 'Programmation'),
('CAT02', 'Développement Web'),
('CAT03', 'Base de données'),
('CAT04', 'Réseau & Sécurité'),
('CAT05', 'Business & Management');

-- --------------------------------------------------------

--
-- Table structure for table `certificat`
--

CREATE TABLE `certificat` (
  `id` int(11) NOT NULL,
  `idapprenant` int(11) NOT NULL,
  `idcours` int(11) NOT NULL,
  `date_emis` date NOT NULL,
  `code_verif` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificat`
--

INSERT INTO `certificat` (`id`, `idapprenant`, `idcours`, `date_emis`, `code_verif`) VALUES
(1, 1, 1, '2024-09-18', 'b13812e2272d4509bef55ebc578bf72f68d04c6a251803e146a5e3958d17db96'),
(2, 4, 3, '2024-11-15', '5f50f8c829c9f83fb76a3639dfc43c73a85506b6b7d98ad53623f989ce3d0aa7');

-- --------------------------------------------------------

--
-- Table structure for table `contenu`
--

CREATE TABLE `contenu` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `urlcontenu` varchar(255) NOT NULL,
  `type` enum('VIDEO','PDF','QUIZ','TEXTE') NOT NULL DEFAULT 'VIDEO',
  `duree` int(11) NOT NULL DEFAULT 0 COMMENT 'en minutes',
  `idmodule` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contenu`
--

INSERT INTO `contenu` (`id`, `titre`, `urlcontenu`, `type`, `duree`, `idmodule`) VALUES
(1, 'Vidéo : Qu\'est-ce que Python ?', 'https://www.youtube.com/embed/kqtD5dpn9C8', 'VIDEO', 15, 1),
(2, 'PDF : Guide d\'installation Python', 'assets/pdf/python_install.pdf', 'PDF', 10, 1),
(3, 'Vidéo : Variables en Python', 'https://www.youtube.com/embed/Z1Yd7upQsXY', 'VIDEO', 20, 2),
(4, 'Quiz : Variables', 'quiz', 'QUIZ', 10, 2),
(5, 'Vidéo : If, For, While', 'https://www.youtube.com/embed/6iF8Xb7Z3wQ', 'VIDEO', 25, 3),
(6, 'Vidéo : Définir des fonctions', 'https://www.youtube.com/embed/9Os0o3wzS_I', 'VIDEO', 30, 4),
(7, 'Quiz : Fonctions', 'quiz', 'QUIZ', 10, 4),
(8, 'Vidéo : Structure HTML5', 'https://www.youtube.com/embed/UB1O30fR-EE', 'VIDEO', 20, 5),
(9, 'Vidéo : Introduction PHP', 'https://www.youtube.com/embed/OK_JCtrrv-c', 'VIDEO', 25, 6),
(10, 'Vidéo : PDO et MySQL', 'https://www.youtube.com/embed/3OJNH-7yvCk', 'VIDEO', 35, 7),
(11, 'Vidéo : Architecture MVC', 'https://www.youtube.com/embed/lZg7AHqBXO4', 'VIDEO', 40, 8),
(12, 'PDF : Sécurité PHP', 'assets/pdf/securite_php.pdf', 'PDF', 20, 9),
(13, 'Vidéo : Introduction SGBD', 'https://www.youtube.com/embed/7S_tz1z_5bA', 'VIDEO', 15, 10),
(14, 'Vidéo : Commandes SQL de base', 'https://www.youtube.com/embed/HXV3zeQKqGY', 'VIDEO', 30, 11),
(15, 'Vidéo : Jointures SQL', 'https://www.youtube.com/embed/9yeOJ0ZMUYw', 'VIDEO', 25, 12),
(16, 'PDF : Modélisation relationnelle', 'assets/pdf/modelisation.pdf', 'PDF', 20, 13);

-- --------------------------------------------------------

--
-- Table structure for table `cours`
--

CREATE TABLE `cours` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `datecreation` date NOT NULL,
  `description` text NOT NULL,
  `montant` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT 'default_course.jpg',
  `niveau` enum('DEBUTANT','INTERMEDIAIRE','AVANCE') NOT NULL DEFAULT 'DEBUTANT',
  `duree_totale` int(11) NOT NULL DEFAULT 0 COMMENT 'en minutes',
  `codcat` varchar(10) NOT NULL,
  `idformateur` int(11) NOT NULL,
  `codmat` varchar(10) NOT NULL,
  `statut` enum('BROUILLON','PUBLIE','ARCHIVE') NOT NULL DEFAULT 'BROUILLON'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cours`
--

INSERT INTO `cours` (`id`, `titre`, `datecreation`, `description`, `montant`, `image`, `niveau`, `duree_totale`, `codcat`, `idformateur`, `codmat`, `statut`) VALUES
(1, 'Introduction à Python', '2024-09-10', 'Apprenez les bases de la programmation Python : variables, boucles, fonctions et structures de données. Ce cours est idéal pour les débutants souhaitant débuter en programmation.', 15000, 'default_course.jpg', 'DEBUTANT', 480, 'CAT01', 1, 'MAT01', 'PUBLIE'),
(2, 'Développement Web avec PHP', '2024-09-20', 'Maîtrisez le développement web côté serveur avec PHP et MySQL. Créez des applications web dynamiques sécurisées en suivant l\'architecture MVC.', 20000, 'default_course.jpg', 'INTERMEDIAIRE', 720, 'CAT02', 1, 'MAT02', 'PUBLIE'),
(3, 'Bases de données MySQL', '2024-10-05', 'Découvrez la conception et la gestion de bases de données relationnelles avec MySQL. Requêtes SQL, jointures, index, procédures stockées et optimisation.', 18000, 'default_course.jpg', 'INTERMEDIAIRE', 600, 'CAT03', 2, 'MAT03', 'PUBLIE');

-- --------------------------------------------------------

--
-- Table structure for table `devoir`
--

CREATE TABLE `devoir` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL DEFAULT 'Devoir',
  `datedebut` date NOT NULL,
  `datefin` date NOT NULL,
  `fichier` varchar(255) DEFAULT NULL,
  `consigne` text NOT NULL,
  `codmat` varchar(10) NOT NULL,
  `codopt` varchar(10) NOT NULL,
  `idformateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devoir`
--

INSERT INTO `devoir` (`id`, `titre`, `datedebut`, `datefin`, `fichier`, `consigne`, `codmat`, `codopt`, `idformateur`) VALUES
(1, 'Exercice Python : Algorithmes de tri', '2024-10-01', '2024-10-15', NULL, 'Implémentez les algorithmes de tri par sélection et par insertion en Python. Testez avec des listes de 10 éléments et commentez votre code.', 'MAT01', 'OPT01', 1),
(2, 'Projet PHP : Mini-application CRUD', '2024-10-20', '2024-11-05', NULL, 'Créez une mini-application PHP/MySQL permettant de gérer une liste de tâches (CRUD complet). Utilisez PDO et l\'architecture MVC simplifiée.', 'MAT02', 'OPT01', 1),
(3, 'Modélisation BDD : Système de bibliothèque', '2024-11-01', '2024-11-20', NULL, 'Concevez le MCD puis le MLD d\'un système de gestion de bibliothèque. Générez le script SQL et insérez 5 enregistrements de test.', 'MAT03', 'OPT02', 2);

-- --------------------------------------------------------

--
-- Table structure for table `enroller`
--

CREATE TABLE `enroller` (
  `idapprenant` int(11) NOT NULL,
  `idcours` int(11) NOT NULL,
  `date_enroll` date NOT NULL,
  `progression` int(11) NOT NULL DEFAULT 0 COMMENT 'pourcentage 0-100',
  `termine` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enroller`
--

INSERT INTO `enroller` (`idapprenant`, `idcours`, `date_enroll`, `progression`, `termine`) VALUES
(1, 1, '2024-09-10', 100, 1),
(1, 2, '2024-09-15', 80, 0),
(2, 1, '2024-09-12', 75, 0),
(2, 3, '2024-10-10', 50, 0),
(3, 2, '2024-09-25', 60, 0),
(3, 3, '2024-10-12', 25, 0),
(4, 3, '2024-10-15', 100, 1),
(5, 1, '2024-10-06', 40, 0);

-- --------------------------------------------------------

--
-- Table structure for table `filiere`
--

CREATE TABLE `filiere` (
  `codefil` varchar(10) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `filiere`
--

INSERT INTO `filiere` (`codefil`, `libelle`) VALUES
('FIL01', 'Informatique'),
('FIL02', 'Gestion'),
('FIL03', 'Marketing Digital');

-- --------------------------------------------------------

--
-- Table structure for table `formateur`
--

CREATE TABLE `formateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dateinscription` date NOT NULL,
  `statut` enum('PERMANENT','VACATAIRE') NOT NULL DEFAULT 'VACATAIRE',
  `valide` tinyint(1) NOT NULL DEFAULT 0,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formateur`
--

INSERT INTO `formateur` (`id`, `nom`, `prenom`, `email`, `password`, `dateinscription`, `statut`, `valide`, `login_attempts`, `locked_until`) VALUES
(1, 'MENSAH', 'Kokou Aristide', 'kokou.mensah@e4afrika.com', '$2y$10$XBNL0Gdlaen90KTr1o8jduViLcO9Cp/elKNP7AClYtw4QcO9QQPoS', '2024-09-01', 'PERMANENT', 1, 0, NULL),
(2, 'DIALLO', 'Aïssatou Fatoumata', 'aissatou.diallo@e4afrika.com', '$2y$10$XBNL0Gdlaen90KTr1o8jduViLcO9Cp/elKNP7AClYtw4QcO9QQPoS', '2024-10-15', 'VACATAIRE', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logs_actions`
--

CREATE TABLE `logs_actions` (
  `id` int(11) NOT NULL,
  `utilisateur` varchar(100) NOT NULL,
  `role` varchar(30) NOT NULL,
  `action` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `date_action` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matiere`
--

CREATE TABLE `matiere` (
  `codmat` varchar(10) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matiere`
--

INSERT INTO `matiere` (`codmat`, `libelle`) VALUES
('MAT01', 'Programmation Python'),
('MAT02', 'Développement Web'),
('MAT03', 'Bases de Données'),
('MAT04', 'Réseaux Informatiques'),
('MAT05', 'Marketing Digital');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `ordre` int(11) NOT NULL DEFAULT 1,
  `idcours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `titre`, `ordre`, `idcours`) VALUES
(1, 'Introduction et Installation', 1, 1),
(2, 'Variables et Types de données', 2, 1),
(3, 'Structures de contrôle', 3, 1),
(4, 'Fonctions et Modules', 4, 1),
(5, 'HTML5 & CSS3 Fondamentaux', 1, 2),
(6, 'PHP : Bases et Syntaxe', 2, 2),
(7, 'PHP & MySQL avec PDO', 3, 2),
(8, 'Architecture MVC', 4, 2),
(9, 'Sécurité des Applications Web', 5, 2),
(10, 'Introduction aux SGBD', 1, 3),
(11, 'Langage SQL : SELECT, INSERT, UPDATE, DELETE', 2, 3),
(12, 'Jointures et Sous-requêtes', 3, 3),
(13, 'Conception Relationnelle', 4, 3);

-- --------------------------------------------------------

--
-- Table structure for table `option`
--

CREATE TABLE `option` (
  `codopt` varchar(10) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `codefil` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `option`
--

INSERT INTO `option` (`codopt`, `libelle`, `codefil`) VALUES
('OPT01', 'Systèmes Informatiques et Logiciels', 'FIL01'),
('OPT02', 'Réseaux et Télécommunications', 'FIL01'),
('OPT03', 'Comptabilité et Finance', 'FIL02'),
('OPT04', 'Commerce et Entrepreneuriat', 'FIL03');

-- --------------------------------------------------------

--
-- Table structure for table `progression_module`
--

CREATE TABLE `progression_module` (
  `idapprenant` int(11) NOT NULL,
  `idmodule` int(11) NOT NULL,
  `complete` tinyint(1) NOT NULL DEFAULT 0,
  `date_compl` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `progression_module`
--

INSERT INTO `progression_module` (`idapprenant`, `idmodule`, `complete`, `date_compl`) VALUES
(1, 1, 1, '2024-09-12 10:30:00'),
(1, 2, 1, '2024-09-14 14:00:00'),
(1, 3, 1, '2024-09-16 09:00:00'),
(1, 4, 1, '2024-09-18 11:00:00'),
(4, 10, 1, '2024-11-01 10:00:00'),
(4, 11, 1, '2024-11-05 14:00:00'),
(4, 12, 1, '2024-11-10 09:30:00'),
(4, 13, 1, '2024-11-15 16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `choix_a` varchar(255) NOT NULL,
  `choix_b` varchar(255) NOT NULL,
  `choix_c` varchar(255) DEFAULT NULL,
  `choix_d` varchar(255) DEFAULT NULL,
  `reponse` enum('A','B','C','D') NOT NULL,
  `idmodule` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`id`, `question`, `choix_a`, `choix_b`, `choix_c`, `choix_d`, `reponse`, `idmodule`) VALUES
(1, 'Quel mot-clé permet de déclarer une variable en Python ?', 'var', 'let', 'Aucun mot-clé nécessaire', 'define', 'C', 2),
(2, 'Quel est le type de la valeur 3.14 en Python ?', 'int', 'float', 'str', 'double', 'B', 2),
(3, 'Comment afficher \"Bonjour\" en Python ?', 'echo \"Bonjour\"', 'console.log(\"Bonjour\")', 'print(\"Bonjour\")', 'printf(\"Bonjour\")', 'C', 4),
(4, 'Quelle extension a un fichier PHP ?', '.html', '.py', '.php', '.js', 'C', 6),
(5, 'Comment ouvrir une balise PHP ?', '<php>', '<?php', '<php!>', '<%php%>', 'B', 6);

-- --------------------------------------------------------

--
-- Table structure for table `reponse_quiz`
--

CREATE TABLE `reponse_quiz` (
  `idapprenant` int(11) NOT NULL,
  `idquiz` int(11) NOT NULL,
  `reponse` enum('A','B','C','D') NOT NULL,
  `correct` tinyint(1) NOT NULL DEFAULT 0,
  `date_rep` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `soumission_devoir`
--

CREATE TABLE `soumission_devoir` (
  `id` int(11) NOT NULL,
  `idapprenant` int(11) NOT NULL,
  `iddevoir` int(11) NOT NULL,
  `fichier` varchar(255) NOT NULL,
  `date_soumis` datetime NOT NULL,
  `note` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `apprenant`
--
ALTER TABLE `apprenant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_apprenant_option` (`codopt`);

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`codcat`);

--
-- Indexes for table `certificat`
--
ALTER TABLE `certificat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_verif` (`code_verif`),
  ADD UNIQUE KEY `uq_certif` (`idapprenant`,`idcours`),
  ADD KEY `fk_cert_cours` (`idcours`);

--
-- Indexes for table `contenu`
--
ALTER TABLE `contenu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contenu_module` (`idmodule`);

--
-- Indexes for table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cours_categorie` (`codcat`),
  ADD KEY `fk_cours_formateur` (`idformateur`),
  ADD KEY `fk_cours_matiere` (`codmat`);

--
-- Indexes for table `devoir`
--
ALTER TABLE `devoir`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_devoir_matiere` (`codmat`),
  ADD KEY `fk_devoir_option` (`codopt`),
  ADD KEY `fk_devoir_formateur` (`idformateur`);

--
-- Indexes for table `enroller`
--
ALTER TABLE `enroller`
  ADD PRIMARY KEY (`idapprenant`,`idcours`),
  ADD KEY `fk_enroller_cours` (`idcours`);

--
-- Indexes for table `filiere`
--
ALTER TABLE `filiere`
  ADD PRIMARY KEY (`codefil`);

--
-- Indexes for table `formateur`
--
ALTER TABLE `formateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `logs_actions`
--
ALTER TABLE `logs_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`codmat`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_module_cours` (`idcours`);

--
-- Indexes for table `option`
--
ALTER TABLE `option`
  ADD PRIMARY KEY (`codopt`),
  ADD KEY `fk_option_filiere` (`codefil`);

--
-- Indexes for table `progression_module`
--
ALTER TABLE `progression_module`
  ADD PRIMARY KEY (`idapprenant`,`idmodule`),
  ADD KEY `fk_prog_module` (`idmodule`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_quiz_module` (`idmodule`);

--
-- Indexes for table `reponse_quiz`
--
ALTER TABLE `reponse_quiz`
  ADD PRIMARY KEY (`idapprenant`,`idquiz`),
  ADD KEY `fk_rep_quiz` (`idquiz`);

--
-- Indexes for table `soumission_devoir`
--
ALTER TABLE `soumission_devoir`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_soumission` (`idapprenant`,`iddevoir`),
  ADD KEY `fk_soum_devoir` (`iddevoir`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `apprenant`
--
ALTER TABLE `apprenant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `certificat`
--
ALTER TABLE `certificat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contenu`
--
ALTER TABLE `contenu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `devoir`
--
ALTER TABLE `devoir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `formateur`
--
ALTER TABLE `formateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `logs_actions`
--
ALTER TABLE `logs_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `soumission_devoir`
--
ALTER TABLE `soumission_devoir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apprenant`
--
ALTER TABLE `apprenant`
  ADD CONSTRAINT `fk_apprenant_option` FOREIGN KEY (`codopt`) REFERENCES `option` (`codopt`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `certificat`
--
ALTER TABLE `certificat`
  ADD CONSTRAINT `fk_cert_apprenant` FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cert_cours` FOREIGN KEY (`idcours`) REFERENCES `cours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contenu`
--
ALTER TABLE `contenu`
  ADD CONSTRAINT `fk_contenu_module` FOREIGN KEY (`idmodule`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `fk_cours_categorie` FOREIGN KEY (`codcat`) REFERENCES `categorie` (`codcat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cours_formateur` FOREIGN KEY (`idformateur`) REFERENCES `formateur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cours_matiere` FOREIGN KEY (`codmat`) REFERENCES `matiere` (`codmat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `devoir`
--
ALTER TABLE `devoir`
  ADD CONSTRAINT `fk_devoir_formateur` FOREIGN KEY (`idformateur`) REFERENCES `formateur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_devoir_matiere` FOREIGN KEY (`codmat`) REFERENCES `matiere` (`codmat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_devoir_option` FOREIGN KEY (`codopt`) REFERENCES `option` (`codopt`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enroller`
--
ALTER TABLE `enroller`
  ADD CONSTRAINT `fk_enroller_apprenant` FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_enroller_cours` FOREIGN KEY (`idcours`) REFERENCES `cours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `fk_module_cours` FOREIGN KEY (`idcours`) REFERENCES `cours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `fk_option_filiere` FOREIGN KEY (`codefil`) REFERENCES `filiere` (`codefil`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `progression_module`
--
ALTER TABLE `progression_module`
  ADD CONSTRAINT `fk_prog_apprenant` FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prog_module` FOREIGN KEY (`idmodule`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `fk_quiz_module` FOREIGN KEY (`idmodule`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reponse_quiz`
--
ALTER TABLE `reponse_quiz`
  ADD CONSTRAINT `fk_rep_apprenant` FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rep_quiz` FOREIGN KEY (`idquiz`) REFERENCES `quiz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `soumission_devoir`
--
ALTER TABLE `soumission_devoir`
  ADD CONSTRAINT `fk_soum_apprenant` FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_soum_devoir` FOREIGN KEY (`iddevoir`) REFERENCES `devoir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
