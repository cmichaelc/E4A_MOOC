-- ============================================================
-- E4Afrika MOOC Platform — Base de données bdmooc
-- Généré pour le mémoire de licence ESSF / Cotonou, Bénin
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `bdmooc`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `bdmooc`;

-- -----------------------------------------------------------
-- Table : administrateur
-- -----------------------------------------------------------
CREATE TABLE `administrateur` (
  `id`       INT          NOT NULL AUTO_INCREMENT,
  `nom`      VARCHAR(30)  NOT NULL,
  `prenom`   VARCHAR(50)  NOT NULL,
  `email`    VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role`     ENUM('SUPER ADMIN','ADMIN','GESTIONNAIRE') NOT NULL DEFAULT 'ADMIN',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : formateur
-- -----------------------------------------------------------
CREATE TABLE `formateur` (
  `id`             INT          NOT NULL AUTO_INCREMENT,
  `nom`            VARCHAR(30)  NOT NULL,
  `prenom`         VARCHAR(50)  NOT NULL,
  `email`          VARCHAR(100) NOT NULL UNIQUE,
  `password`       VARCHAR(255) NOT NULL,
  `dateinscription` DATE        NOT NULL,
  `statut`         ENUM('PERMANENT','VACATAIRE') NOT NULL DEFAULT 'VACATAIRE',
  `valide`         TINYINT(1)   NOT NULL DEFAULT 0,
  `login_attempts` INT          NOT NULL DEFAULT 0,
  `locked_until`   DATETIME     NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : filiere
-- -----------------------------------------------------------
CREATE TABLE `filiere` (
  `codefil` VARCHAR(10)  NOT NULL,
  `libelle` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`codefil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : option
-- -----------------------------------------------------------
CREATE TABLE `option` (
  `codopt`  VARCHAR(10)  NOT NULL,
  `libelle` VARCHAR(100) NOT NULL,
  `codefil` VARCHAR(10)  NOT NULL,
  PRIMARY KEY (`codopt`),
  CONSTRAINT `fk_option_filiere`
    FOREIGN KEY (`codefil`) REFERENCES `filiere` (`codefil`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : matiere
-- -----------------------------------------------------------
CREATE TABLE `matiere` (
  `codmat`  VARCHAR(10)  NOT NULL,
  `libelle` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`codmat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : categorie
-- -----------------------------------------------------------
CREATE TABLE `categorie` (
  `codcat`  VARCHAR(10)  NOT NULL,
  `libelle` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`codcat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : apprenant
-- -----------------------------------------------------------
CREATE TABLE `apprenant` (
  `id`              INT          NOT NULL AUTO_INCREMENT,
  `nom`             VARCHAR(30)  NOT NULL,
  `prenom`          VARCHAR(50)  NOT NULL,
  `email`           VARCHAR(100) NOT NULL UNIQUE,
  `password`        VARCHAR(255) NOT NULL,
  `dateinscription` DATE         NOT NULL,
  `statut`          ENUM('ACTIF','SUSPENDU','TERMINE','ABANDON') NOT NULL DEFAULT 'ACTIF',
  `codopt`          VARCHAR(10)  NOT NULL,
  `login_attempts`  INT          NOT NULL DEFAULT 0,
  `locked_until`    DATETIME     NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_apprenant_option`
    FOREIGN KEY (`codopt`) REFERENCES `option` (`codopt`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : cours
-- -----------------------------------------------------------
CREATE TABLE `cours` (
  `id`           INT          NOT NULL AUTO_INCREMENT,
  `titre`        VARCHAR(255) NOT NULL,
  `datecreation` DATE         NOT NULL,
  `description`  TEXT         NOT NULL,
  `montant`      INT          NOT NULL DEFAULT 0,
  `image`        VARCHAR(255) NULL DEFAULT 'default_course.jpg',
  `niveau`       ENUM('DEBUTANT','INTERMEDIAIRE','AVANCE') NOT NULL DEFAULT 'DEBUTANT',
  `duree_totale` INT          NOT NULL DEFAULT 0 COMMENT 'en minutes',
  `codcat`       VARCHAR(10)  NOT NULL,
  `idformateur`  INT          NOT NULL,
  `codmat`       VARCHAR(10)  NOT NULL,
  `statut`       ENUM('BROUILLON','PUBLIE','ARCHIVE') NOT NULL DEFAULT 'BROUILLON',
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cours_categorie`
    FOREIGN KEY (`codcat`) REFERENCES `categorie` (`codcat`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cours_formateur`
    FOREIGN KEY (`idformateur`) REFERENCES `formateur` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cours_matiere`
    FOREIGN KEY (`codmat`) REFERENCES `matiere` (`codmat`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : module
-- -----------------------------------------------------------
CREATE TABLE `module` (
  `id`       INT          NOT NULL AUTO_INCREMENT,
  `titre`    VARCHAR(255) NOT NULL,
  `ordre`    INT          NOT NULL DEFAULT 1,
  `idcours`  INT          NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_module_cours`
    FOREIGN KEY (`idcours`) REFERENCES `cours` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : contenu
-- -----------------------------------------------------------
CREATE TABLE `contenu` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `titre`      VARCHAR(255) NOT NULL,
  `urlcontenu` VARCHAR(255) NOT NULL,
  `type`       ENUM('VIDEO','PDF','QUIZ','TEXTE') NOT NULL DEFAULT 'VIDEO',
  `duree`      INT          NOT NULL DEFAULT 0 COMMENT 'en minutes',
  `idmodule`   INT          NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_contenu_module`
    FOREIGN KEY (`idmodule`) REFERENCES `module` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : quiz
-- -----------------------------------------------------------
CREATE TABLE `quiz` (
  `id`       INT          NOT NULL AUTO_INCREMENT,
  `question` TEXT         NOT NULL,
  `choix_a`  VARCHAR(255) NOT NULL,
  `choix_b`  VARCHAR(255) NOT NULL,
  `choix_c`  VARCHAR(255) NULL,
  `choix_d`  VARCHAR(255) NULL,
  `reponse`  ENUM('A','B','C','D') NOT NULL,
  `idmodule` INT          NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_quiz_module`
    FOREIGN KEY (`idmodule`) REFERENCES `module` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : devoir
-- -----------------------------------------------------------
CREATE TABLE `devoir` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `titre`       VARCHAR(255) NOT NULL DEFAULT 'Devoir',
  `datedebut`   DATE         NOT NULL,
  `datefin`     DATE         NOT NULL,
  `fichier`     VARCHAR(255) NULL,
  `consigne`    TEXT         NOT NULL,
  `codmat`      VARCHAR(10)  NOT NULL,
  `codopt`      VARCHAR(10)  NOT NULL,
  `idformateur` INT          NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_devoir_matiere`
    FOREIGN KEY (`codmat`) REFERENCES `matiere` (`codmat`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_devoir_option`
    FOREIGN KEY (`codopt`) REFERENCES `option` (`codopt`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_devoir_formateur`
    FOREIGN KEY (`idformateur`) REFERENCES `formateur` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : enroller (inscription cours)
-- -----------------------------------------------------------
CREATE TABLE `enroller` (
  `idapprenant`   INT      NOT NULL,
  `idcours`       INT      NOT NULL,
  `date_enroll`   DATE     NOT NULL,
  `progression`   INT      NOT NULL DEFAULT 0 COMMENT 'pourcentage 0-100',
  `termine`       TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idapprenant`, `idcours`),
  CONSTRAINT `fk_enroller_apprenant`
    FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_enroller_cours`
    FOREIGN KEY (`idcours`) REFERENCES `cours` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : soumission_devoir
-- -----------------------------------------------------------
CREATE TABLE `soumission_devoir` (
  `id`           INT          NOT NULL AUTO_INCREMENT,
  `idapprenant`  INT          NOT NULL,
  `iddevoir`     INT          NOT NULL,
  `fichier`      VARCHAR(255) NOT NULL,
  `date_soumis`  DATETIME     NOT NULL,
  `note`         DECIMAL(5,2) NULL DEFAULT NULL,
  `feedback`     TEXT         NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_soumission` (`idapprenant`, `iddevoir`),
  CONSTRAINT `fk_soum_apprenant`
    FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_soum_devoir`
    FOREIGN KEY (`iddevoir`) REFERENCES `devoir` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : progression_module
-- -----------------------------------------------------------
CREATE TABLE `progression_module` (
  `idapprenant` INT  NOT NULL,
  `idmodule`    INT  NOT NULL,
  `complete`    TINYINT(1) NOT NULL DEFAULT 0,
  `date_compl`  DATETIME   NULL DEFAULT NULL,
  PRIMARY KEY (`idapprenant`, `idmodule`),
  CONSTRAINT `fk_prog_apprenant`
    FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_prog_module`
    FOREIGN KEY (`idmodule`) REFERENCES `module` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : reponse_quiz
-- -----------------------------------------------------------
CREATE TABLE `reponse_quiz` (
  `idapprenant` INT              NOT NULL,
  `idquiz`      INT              NOT NULL,
  `reponse`     ENUM('A','B','C','D') NOT NULL,
  `correct`     TINYINT(1)       NOT NULL DEFAULT 0,
  `date_rep`    DATETIME         NOT NULL,
  PRIMARY KEY (`idapprenant`, `idquiz`),
  CONSTRAINT `fk_rep_apprenant`
    FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rep_quiz`
    FOREIGN KEY (`idquiz`) REFERENCES `quiz` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : certificat
-- -----------------------------------------------------------
CREATE TABLE `certificat` (
  `id`           INT          NOT NULL AUTO_INCREMENT,
  `idapprenant`  INT          NOT NULL,
  `idcours`      INT          NOT NULL,
  `date_emis`    DATE         NOT NULL,
  `code_verif`   VARCHAR(64)  NOT NULL UNIQUE,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_certif` (`idapprenant`, `idcours`),
  CONSTRAINT `fk_cert_apprenant`
    FOREIGN KEY (`idapprenant`) REFERENCES `apprenant` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cert_cours`
    FOREIGN KEY (`idcours`) REFERENCES `cours` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------------
-- Table : logs_actions
-- -----------------------------------------------------------
CREATE TABLE `logs_actions` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `utilisateur` VARCHAR(100) NOT NULL,
  `role`       VARCHAR(30)  NOT NULL,
  `action`     VARCHAR(255) NOT NULL,
  `ip`         VARCHAR(45)  NOT NULL,
  `date_action` DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DONNÉES DE DÉMONSTRATION
-- ============================================================

-- Filières
INSERT INTO `filiere` (`codefil`, `libelle`) VALUES
('FIL01', 'Informatique'),
('FIL02', 'Gestion'),
('FIL03', 'Marketing Digital');

-- Options
INSERT INTO `option` (`codopt`, `libelle`, `codefil`) VALUES
('OPT01', 'Systèmes Informatiques et Logiciels', 'FIL01'),
('OPT02', 'Réseaux et Télécommunications', 'FIL01'),
('OPT03', 'Comptabilité et Finance', 'FIL02'),
('OPT04', 'Commerce et Entrepreneuriat', 'FIL03');

-- Matières
INSERT INTO `matiere` (`codmat`, `libelle`) VALUES
('MAT01', 'Programmation Python'),
('MAT02', 'Développement Web'),
('MAT03', 'Bases de Données'),
('MAT04', 'Réseaux Informatiques'),
('MAT05', 'Marketing Digital');

-- Catégories
INSERT INTO `categorie` (`codcat`, `libelle`) VALUES
('CAT01', 'Programmation'),
('CAT02', 'Développement Web'),
('CAT03', 'Base de données'),
('CAT04', 'Réseau & Sécurité'),
('CAT05', 'Business & Management');

-- Administrateur (password: Admin@1234)
INSERT INTO `administrateur` (`nom`, `prenom`, `email`, `password`, `role`) VALUES
('HOUNKPE', 'Rodrigue', 'admin@e4afrika.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkSiBKi', 'SUPER ADMIN');

-- Formateurs (password: Formateur@1)
INSERT INTO `formateur` (`nom`, `prenom`, `email`, `password`, `dateinscription`, `statut`, `valide`) VALUES
('MENSAH', 'Kokou Aristide', 'kokou.mensah@e4afrika.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkSiBKi', '2024-09-01', 'PERMANENT', 1),
('DIALLO', 'Aïssatou Fatoumata', 'aissatou.diallo@e4afrika.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkSiBKi', '2024-10-15', 'VACATAIRE', 1);

-- Apprenants (password: Apprenant@1)
INSERT INTO `apprenant` (`nom`, `prenom`, `email`, `password`, `dateinscription`, `statut`, `codopt`) VALUES
('AGOSSOU', 'Fidèle Joëlle', 'fidele.agossou@email.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkSiBKi', '2024-09-05', 'ACTIF', 'OPT01'),
('BOCO', 'Gervais Marius', 'gervais.boco@email.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkSiBKi', '2024-09-07', 'ACTIF', 'OPT01'),
('AZONDEKON', 'Mariette Cécile', 'mariette.azondekon@email.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkSiBKi', '2024-09-10', 'ACTIF', 'OPT02'),
('GBENOU', 'Didier Ulrich', 'didier.gbenou@email.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkSiBKi', '2024-10-01', 'ACTIF', 'OPT03'),
('KAKPO', 'Sylvie Nadège', 'sylvie.kakpo@email.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXkSiBKi', '2024-10-05', 'ACTIF', 'OPT04');

-- Cours
INSERT INTO `cours` (`titre`, `datecreation`, `description`, `montant`, `niveau`, `duree_totale`, `codcat`, `idformateur`, `codmat`, `statut`) VALUES
('Introduction à Python', '2024-09-10',
 'Apprenez les bases de la programmation Python : variables, boucles, fonctions et structures de données. Ce cours est idéal pour les débutants souhaitant débuter en programmation.',
 15000, 'DEBUTANT', 480, 'CAT01', 1, 'MAT01', 'PUBLIE'),
('Développement Web avec PHP', '2024-09-20',
 'Maîtrisez le développement web côté serveur avec PHP et MySQL. Créez des applications web dynamiques sécurisées en suivant l''architecture MVC.',
 20000, 'INTERMEDIAIRE', 720, 'CAT02', 1, 'MAT02', 'PUBLIE'),
('Bases de données MySQL', '2024-10-05',
 'Découvrez la conception et la gestion de bases de données relationnelles avec MySQL. Requêtes SQL, jointures, index, procédures stockées et optimisation.',
 18000, 'INTERMEDIAIRE', 600, 'CAT03', 2, 'MAT03', 'PUBLIE');

-- Modules cours 1 : Python
INSERT INTO `module` (`titre`, `ordre`, `idcours`) VALUES
('Introduction et Installation', 1, 1),
('Variables et Types de données', 2, 1),
('Structures de contrôle', 3, 1),
('Fonctions et Modules', 4, 1);

-- Modules cours 2 : PHP
INSERT INTO `module` (`titre`, `ordre`, `idcours`) VALUES
('HTML5 & CSS3 Fondamentaux', 1, 2),
('PHP : Bases et Syntaxe', 2, 2),
('PHP & MySQL avec PDO', 3, 2),
('Architecture MVC', 4, 2),
('Sécurité des Applications Web', 5, 2);

-- Modules cours 3 : MySQL
INSERT INTO `module` (`titre`, `ordre`, `idcours`) VALUES
('Introduction aux SGBD', 1, 3),
('Langage SQL : SELECT, INSERT, UPDATE, DELETE', 2, 3),
('Jointures et Sous-requêtes', 3, 3),
('Conception Relationnelle', 4, 3);

-- Contenus (Python)
INSERT INTO `contenu` (`titre`, `urlcontenu`, `type`, `duree`, `idmodule`) VALUES
('Vidéo : Qu''est-ce que Python ?', 'https://www.youtube.com/embed/kqtD5dpn9C8', 'VIDEO', 15, 1),
('PDF : Guide d''installation Python', 'assets/pdf/python_install.pdf', 'PDF', 10, 1),
('Vidéo : Variables en Python', 'https://www.youtube.com/embed/Z1Yd7upQsXY', 'VIDEO', 20, 2),
('Quiz : Variables', 'quiz', 'QUIZ', 10, 2),
('Vidéo : If, For, While', 'https://www.youtube.com/embed/6iF8Xb7Z3wQ', 'VIDEO', 25, 3),
('Vidéo : Définir des fonctions', 'https://www.youtube.com/embed/9Os0o3wzS_I', 'VIDEO', 30, 4),
('Quiz : Fonctions', 'quiz', 'QUIZ', 10, 4);

-- Contenus (PHP)
INSERT INTO `contenu` (`titre`, `urlcontenu`, `type`, `duree`, `idmodule`) VALUES
('Vidéo : Structure HTML5', 'https://www.youtube.com/embed/UB1O30fR-EE', 'VIDEO', 20, 5),
('Vidéo : Introduction PHP', 'https://www.youtube.com/embed/OK_JCtrrv-c', 'VIDEO', 25, 6),
('Vidéo : PDO et MySQL', 'https://www.youtube.com/embed/3OJNH-7yvCk', 'VIDEO', 35, 7),
('Vidéo : Architecture MVC', 'https://www.youtube.com/embed/lZg7AHqBXO4', 'VIDEO', 40, 8),
('PDF : Sécurité PHP', 'assets/pdf/securite_php.pdf', 'PDF', 20, 9);

-- Contenus (MySQL)
INSERT INTO `contenu` (`titre`, `urlcontenu`, `type`, `duree`, `idmodule`) VALUES
('Vidéo : Introduction SGBD', 'https://www.youtube.com/embed/7S_tz1z_5bA', 'VIDEO', 15, 10),
('Vidéo : Commandes SQL de base', 'https://www.youtube.com/embed/HXV3zeQKqGY', 'VIDEO', 30, 11),
('Vidéo : Jointures SQL', 'https://www.youtube.com/embed/9yeOJ0ZMUYw', 'VIDEO', 25, 12),
('PDF : Modélisation relationnelle', 'assets/pdf/modelisation.pdf', 'PDF', 20, 13);

-- Quiz (Module Python - Variables)
INSERT INTO `quiz` (`question`, `choix_a`, `choix_b`, `choix_c`, `choix_d`, `reponse`, `idmodule`) VALUES
('Quel mot-clé permet de déclarer une variable en Python ?', 'var', 'let', 'Aucun mot-clé nécessaire', 'define', 'C', 2),
('Quel est le type de la valeur 3.14 en Python ?', 'int', 'float', 'str', 'double', 'B', 2),
('Comment afficher "Bonjour" en Python ?', 'echo "Bonjour"', 'console.log("Bonjour")', 'print("Bonjour")', 'printf("Bonjour")', 'C', 4);

-- Quiz (Module PHP)
INSERT INTO `quiz` (`question`, `choix_a`, `choix_b`, `choix_c`, `choix_d`, `reponse`, `idmodule`) VALUES
('Quelle extension a un fichier PHP ?', '.html', '.py', '.php', '.js', 'C', 6),
('Comment ouvrir une balise PHP ?', '<php>', '<?php', '<php!>', '<%php%>', 'B', 6);

-- Enrollments avec progressions
INSERT INTO `enroller` (`idapprenant`, `idcours`, `date_enroll`, `progression`, `termine`) VALUES
(1, 1, '2024-09-10', 100, 1),
(1, 2, '2024-09-15', 80, 0),
(2, 1, '2024-09-12', 75, 0),
(2, 3, '2024-10-10', 50, 0),
(3, 2, '2024-09-25', 60, 0),
(3, 3, '2024-10-12', 25, 0),
(4, 3, '2024-10-15', 100, 1),
(5, 1, '2024-10-06', 40, 0);

-- Progressions modules (Apprenant 1 - cours 1 terminé)
INSERT INTO `progression_module` (`idapprenant`, `idmodule`, `complete`, `date_compl`) VALUES
(1, 1, 1, '2024-09-12 10:30:00'),
(1, 2, 1, '2024-09-14 14:00:00'),
(1, 3, 1, '2024-09-16 09:00:00'),
(1, 4, 1, '2024-09-18 11:00:00'),
(4, 10, 1, '2024-11-01 10:00:00'),
(4, 11, 1, '2024-11-05 14:00:00'),
(4, 12, 1, '2024-11-10 09:30:00'),
(4, 13, 1, '2024-11-15 16:00:00');

-- Certificats (apprenants ayant terminé)
INSERT INTO `certificat` (`idapprenant`, `idcours`, `date_emis`, `code_verif`) VALUES
(1, 1, '2024-09-18', SHA2(CONCAT('1-1-2024-09-18', RAND()), 256)),
(4, 3, '2024-11-15', SHA2(CONCAT('4-3-2024-11-15', RAND()), 256));

-- Devoirs
INSERT INTO `devoir` (`titre`, `datedebut`, `datefin`, `consigne`, `codmat`, `codopt`, `idformateur`) VALUES
('Exercice Python : Algorithmes de tri', '2024-10-01', '2024-10-15',
 'Implémentez les algorithmes de tri par sélection et par insertion en Python. Testez avec des listes de 10 éléments et commentez votre code.',
 'MAT01', 'OPT01', 1),
('Projet PHP : Mini-application CRUD', '2024-10-20', '2024-11-05',
 'Créez une mini-application PHP/MySQL permettant de gérer une liste de tâches (CRUD complet). Utilisez PDO et l''architecture MVC simplifiée.',
 'MAT02', 'OPT01', 1),
('Modélisation BDD : Système de bibliothèque', '2024-11-01', '2024-11-20',
 'Concevez le MCD puis le MLD d''un système de gestion de bibliothèque. Générez le script SQL et insérez 5 enregistrements de test.',
 'MAT03', 'OPT02', 2);
