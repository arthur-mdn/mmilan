-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 28 Octobre 2022 à 16:47
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `mmilan`
--

-- --------------------------------------------------------

--
-- Structure de la table `appartient`
--

CREATE TABLE `appartient` (
  `AppartientId` int(11) NOT NULL,
  `AppartientPlayerId` int(11) NOT NULL,
  `AppartientTeamId` int(11) NOT NULL,
  `AppartientRole` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'member',
  `AppartientStatus` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ok'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `appartient_solo`
--

CREATE TABLE `appartient_solo` (
  `AppartientSoloId` int(11) NOT NULL,
  `AppartientSoloPlayerId` int(11) NOT NULL,
  `AppartientSoloStatus` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ok'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `games`
--

CREATE TABLE `games` (
  `GameId` int(11) NOT NULL,
  `GameName` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `GameDescription` text COLLATE utf8_unicode_ci NOT NULL,
  `GamePicture` text COLLATE utf8_unicode_ci NOT NULL,
  `GameStatus` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ok'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `games`
--

INSERT INTO `games` (`GameId`, `GameName`, `GameDescription`, `GamePicture`, `GameStatus`) VALUES
(1, 'Rocket League', 'Rocket League est un jeu vidéo. Oui oui !', 'Elements/games/rocket_league.jpg', 'ok'),
(2, 'Overwatch 2', 'Overwatch 2 est aussi un jeu vidéo.', 'Elements/games/Overwatch_2.svg', 'ok'),
(3, 'Trackmania', 'Bizarrement, Trackmania est aussi un jeu vidéo.', 'Elements/games/trackmania.png', 'ok'),
(4, 'Fall Guys', 'Par contre Fall Guys est-il un jeu vidéo ? Oui', 'Elements/games/fall_guys.png', 'ok'),
(5, 'Switch Sport', 'Bon, ben j\'en conclus que Switch Sport est un jeu vidéo aussi...', 'Elements/games/switch_sport.png', 'ok');

-- --------------------------------------------------------

--
-- Structure de la table `invitations`
--

CREATE TABLE `invitations` (
  `InvitationId` int(11) NOT NULL,
  `InvitationTeamId` int(11) NOT NULL,
  `InvitationEmail` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `InvitationToken` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `InvitationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `InvitationStatus` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ok'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `LogId` int NOT NULL AUTO_INCREMENT,
  `LogMsg` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `LogUserMail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `LogDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`LogId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------


--
-- Structure de la table `players`
--

CREATE TABLE `players` (
  `PlayerId` int(11) NOT NULL,
  `PlayerLastname` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `PlayerFirstname` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `PlayerUsername` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `PlayerTel` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `PlayerEmail` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `PlayerDiscord` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `PlayerFavGameId` int(11) NOT NULL,
  `PlayerPassword` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
  `PlayerCreation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `PlayerStatus` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ok',
  `PlayerProfil` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'other',
  `PlayerRole` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `PlayerNotes` varchar(1024) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reinitialisation`
--

CREATE TABLE `reinitialisation` (
  `CodeReinitialisation` int(11) NOT NULL,
  `DateReinitialisation` datetime NOT NULL,
  `TokenReinitialisation` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `PlayerId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `SettingsName` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `SettingsDescription` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `SettingsValue` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `settings`
--

INSERT INTO `settings` (`SettingsName`, `SettingsDescription`, `SettingsValue`) VALUES
('instance_email_host', 'URL de l\'instance email', 'ENTER YOUR URL MAIL SMTP CONFIG'),
('instance_email_password', 'Mot de passe Email de l\'instance', 'ENTER YOUR PASSWORD MAIL SMTP CONFIG'),
('instance_email_port', 'Port Email de l\'instance', 'ENTER YOUR PORT MAIL SMTP CONFIG'),
('instance_email_support', 'Email SUPPORT de l\'instance', 'contact@mondon.pro'),
('instance_email_username', 'Email de l\'instance', 'ENTER YOUR URL USERNAME SMTP CONFIG'),
('instance_status', 'Status de l\'instance Calendry', 'dev'),
('instance_url', 'URL de l\'instance', 'ENTER YOUR URL WORKING FOLDER'),
('name', 'nom de l\'instance', 'MMILAN');

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

CREATE TABLE `teams` (
  `TeamId` int(11) NOT NULL,
  `TeamName` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `TeamDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `TeamLogo` longtext COLLATE utf8_unicode_ci NOT NULL,
  `TeamStatus` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ok',
  `TeamRank` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tentative`
--

CREATE TABLE `tentative` (
  `CodeTentative` int(11) NOT NULL,
  `DateTentative` datetime NOT NULL,
  `LibTentative` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `PlayerId` int(11) NOT NULL,
  `StatusTentative` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `appartient`
--
ALTER TABLE `appartient`
  ADD PRIMARY KEY (`AppartientId`),
  ADD KEY `AppartientPlayerId` (`AppartientPlayerId`),
  ADD KEY `AppartientTeamId` (`AppartientTeamId`);

--
-- Index pour la table `appartient_solo`
--
ALTER TABLE `appartient_solo`
  ADD PRIMARY KEY (`AppartientSoloId`),
  ADD KEY `AppartientPlayerId` (`AppartientSoloPlayerId`);

--
-- Index pour la table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`GameId`);

--
-- Index pour la table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`InvitationId`),
  ADD KEY `InvitationTeamId` (`InvitationTeamId`);

--
-- Index pour la table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`PlayerId`),
  ADD UNIQUE KEY `id` (`PlayerId`),
  ADD KEY `PlayerFavGameId` (`PlayerFavGameId`);

--
-- Index pour la table `reinitialisation`
--
ALTER TABLE `reinitialisation`
  ADD PRIMARY KEY (`CodeReinitialisation`),
  ADD UNIQUE KEY `CodeReinitialisation` (`CodeReinitialisation`),
  ADD KEY `reinitialisations_utilisateur_FK` (`PlayerId`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`SettingsName`),
  ADD UNIQUE KEY `name` (`SettingsName`);

--
-- Index pour la table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`TeamId`);

--
-- Index pour la table `tentative`
--
ALTER TABLE `tentative`
  ADD PRIMARY KEY (`CodeTentative`),
  ADD UNIQUE KEY `CodeTentative` (`CodeTentative`),
  ADD KEY `tentative_utilisateur_FK` (`PlayerId`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `appartient`
--
ALTER TABLE `appartient`
  MODIFY `AppartientId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `appartient_solo`
--
ALTER TABLE `appartient_solo`
  MODIFY `AppartientSoloId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `games`
--
ALTER TABLE `games`
  MODIFY `GameId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `InvitationId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `players`
--
ALTER TABLE `players`
  MODIFY `PlayerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `reinitialisation`
--
ALTER TABLE `reinitialisation`
  MODIFY `CodeReinitialisation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `teams`
--
ALTER TABLE `teams`
  MODIFY `TeamId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tentative`
--
ALTER TABLE `tentative`
  MODIFY `CodeTentative` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `appartient`
--
ALTER TABLE `appartient`
  ADD CONSTRAINT `appartient_group` FOREIGN KEY (`AppartientTeamId`) REFERENCES `teams` (`TeamId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appartient_player` FOREIGN KEY (`AppartientPlayerId`) REFERENCES `players` (`PlayerId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `appartient_solo`
--
ALTER TABLE `appartient_solo`
  ADD CONSTRAINT `appartient_solo_player_id` FOREIGN KEY (`AppartientSoloPlayerId`) REFERENCES `players` (`PlayerId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitation_team` FOREIGN KEY (`InvitationTeamId`) REFERENCES `teams` (`TeamId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `game_id` FOREIGN KEY (`PlayerFavGameId`) REFERENCES `games` (`GameId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reinitialisation`
--
ALTER TABLE `reinitialisation`
  ADD CONSTRAINT `player_id_reinitialisation` FOREIGN KEY (`PlayerId`) REFERENCES `players` (`PlayerId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `tentative`
--
ALTER TABLE `tentative`
  ADD CONSTRAINT `player_tentative` FOREIGN KEY (`PlayerId`) REFERENCES `players` (`PlayerId`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;