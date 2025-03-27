-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 02 nov. 2022 à 23:30
-- Version du serveur : 10.4.21-MariaDB
-- Version de PHP : 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `notes_esigelec`
--

-- --------------------------------------------------------

--
-- Structure de la table `tblclasses`
--

CREATE TABLE `tblclasses` (
  `id` int(11) NOT NULL,
  `ClassName` varchar(80) DEFAULT NULL,
  `ClassNameNumeric` int(4) DEFAULT NULL,
  `Section` varchar(5) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblclasses`
--

INSERT INTO `tblclasses` (`id`, `ClassName`, `ClassNameNumeric`, `Section`, `CreationDate`, `UpdationDate`) VALUES
(1, '1ère année', 1, 'TIC', '2022-09-04 08:31:45', NULL),
(63, '2ème année', 2, 'TIC', '2022-09-04 09:55:02', NULL),
(64, '3ème année', 3, 'TIC', '2022-10-19 15:24:33', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tblnotice`
--

CREATE TABLE `tblnotice` (
  `id` int(11) NOT NULL,
  `noticeTitle` varchar(255) DEFAULT NULL,
  `noticeDetails` mediumtext DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tblnotice`
--

INSERT INTO `tblnotice` (`id`, `noticeTitle`, `noticeDetails`, `postingDate`) VALUES
(1, 'Conférence débat autour du handicap, jeudi 20 octobre', 'Conférence débat autour du handicap, jeudi 20 octobre\r\n', '2022-09-04 08:42:47'),
(2, 'Actions et ressources de l\'OBSERVATOIRE DES METIERS de l\'IMT (Institut Mines Télécom)\n', 'Holiday Homework of Summer vacation 2022 – 2023 has been uploaded and you can download it by clicking on the link.Actions et ressources de l\'OBSERVATOIRE DES METIERS de l\'IMT (Institut Mines Télécom)\n', '2022-09-04 08:43:19');

-- --------------------------------------------------------

--
-- Structure de la table `tblresult`
--

CREATE TABLE `tblresult` (
  `id` int(11) NOT NULL,
  `StudentId` int(11) DEFAULT NULL,
  `ClassId` int(11) DEFAULT NULL,
  `SubjectId` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblresult`
--

INSERT INTO `tblresult` (`id`, `StudentId`, `ClassId`, `SubjectId`, `marks`, `PostingDate`, `UpdationDate`) VALUES
(1, 1, 1, 2, 15, '2022-09-04 08:41:18', NULL),
(2, 1, 1, 3, 18, '2022-09-04 08:41:18', NULL),
(3, 1, 1, 5, 16, '2022-09-04 08:41:18', NULL),
(4, 1, 1, 1, 19, '2022-09-04 08:41:18', NULL),
(5, 1, 1, 4, 14, '2022-09-04 08:41:18', NULL),
(6, 3, 1, 2, 10, '2022-09-04 09:56:54', NULL),
(7, 3, 1, 3, 12, '2022-09-04 09:56:54', NULL),
(8, 3, 1, 5, 12, '2022-09-04 09:56:54', NULL),
(9, 3, 1, 1, 15, '2022-09-04 09:56:54', NULL),
(10, 3, 1, 4, 13, '2022-09-04 09:56:54', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `StudentId` int(11) NOT NULL,
  `StudentName` varchar(100) DEFAULT NULL,
  `RollId` int(100) DEFAULT NULL,
  `StudentEmail` varchar(100) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `DOB` varchar(100) DEFAULT NULL,
  `ClassId` int(11) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL,
  `Status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblstudents`
--

INSERT INTO `tblstudents` (`StudentId`, `StudentName`, `RollId`, `StudentEmail`, `Gender`, `DOB`, `ClassId`, `RegDate`, `UpdationDate`, `Status`) VALUES
(1, 'Eva Saunier', 1, 'eva.saunier@esigCyber.fr', 'Female', '1991-09-06', 1, '2022-09-04 08:38:05', NULL, 1),
(2, 'Adam Yakan', 2, 'adamyakan@esigCyber.fr', 'Male', '1992-08-31', 1, '2022-09-04 08:38:32', NULL, 1),
(3, 'Sasha Varma', 3, 'susha.varma@esigCyber.fr', 'Female', '1998-09-02', 1, '2022-09-04 09:56:15', NULL, 1),
(4, 'Nassim Trabelsi', 4, 'zaheryamak@gmail.com', 'Male', '2006-06-06', 1, '2022-10-17 14:17:27', NULL, 1),
(5, 'Julien Vercouter', 5, 'julien.vercouter@esigCyber.fr', 'Male', '2003-05-29', 1, '2022-10-17 14:19:09', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `tblsubjectcombination`
--

CREATE TABLE `tblsubjectcombination` (
  `id` int(11) NOT NULL,
  `ClassId` int(11) DEFAULT NULL,
  `SubjectId` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `Updationdate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblsubjectcombination`
--

INSERT INTO `tblsubjectcombination` (`id`, `ClassId`, `SubjectId`, `status`, `CreationDate`, `Updationdate`) VALUES
(7, 64, 6, 1, '2022-10-19 15:25:34', NULL),
(8, 1, 2, 1, '2022-10-19 15:25:40', '2022-10-19 15:28:23'),
(9, 1, 3, 1, '2022-10-19 15:25:47', NULL),
(10, 63, 1, 1, '2022-10-19 15:25:53', NULL),
(11, 63, 4, 1, '2022-10-19 15:25:59', NULL),
(12, 63, 5, 1, '2022-10-19 15:26:04', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `id` int(11) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `SubjectCode` varchar(100) DEFAULT NULL,
  `Creationdate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblsubjects`
--

INSERT INTO `tblsubjects` (`id`, `SubjectName`, `SubjectCode`, `Creationdate`, `UpdationDate`) VALUES
(1, 'Cybersécurité', 'CS', '2022-09-04 08:36:08', NULL),
(2, 'Java', 'JV', '2022-09-04 08:39:32', NULL),
(3, 'Anglais', 'AN', '2022-09-04 08:39:44', NULL),
(4, 'Réseaux', 'RS', '2022-09-04 08:39:53', NULL),
(5, 'Dev. Web', 'DW', '2022-09-04 08:40:03', NULL),
(6, 'Intelligence Artificielle', 'IA', '2022-09-04 09:55:25', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `updationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `UserName`, `Password`, `is_admin`, `updationDate`) VALUES
(1, 'admin.ecole@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 1, '2022-09-04 10:30:57'),
(2, 'scolarite@esig.fr', '13e97ae45a3e28341a2769efe52effab', 0, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `tblclasses`
--
ALTER TABLE `tblclasses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tblnotice`
--
ALTER TABLE `tblnotice`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tblresult`
--
ALTER TABLE `tblresult`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`StudentId`),
  ADD UNIQUE KEY `RollId` (`RollId`);

--
-- Index pour la table `tblsubjectcombination`
--
ALTER TABLE `tblsubjectcombination`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `tblclasses`
--
ALTER TABLE `tblclasses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `tblnotice`
--
ALTER TABLE `tblnotice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `tblresult`
--
ALTER TABLE `tblresult`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `StudentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `tblsubjectcombination`
--
ALTER TABLE `tblsubjectcombination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
