-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 19, 2022 at 01:12 PM
-- Server version: 10.5.12-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u939917173_demol`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_Awards`
--

CREATE TABLE `table_Awards` (
  `Id` int(11) NOT NULL,
  `Naam` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Beschrijving` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Editie` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Actief` int(11) NOT NULL DEFAULT 0 COMMENT '0 = Uit / 1 = Aan',
  `Secret` tinyint(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_Candidates`
--

CREATE TABLE `table_Candidates` (
  `Id` int(11) NOT NULL,
  `Name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Age` tinyint(3) NOT NULL,
  `Job` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Is this candidate still in the game?',
  `Mol` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is this candidate the mole?',
  `Winner` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is this candidate the winner?',
  `Loser` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is this candidate the losing finalist?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_Friends`
--

CREATE TABLE `table_Friends` (
  `Id` int(11) NOT NULL,
  `IsFriendsWithId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_Groups`
--

CREATE TABLE `table_Groups` (
  `Id` int(11) NOT NULL,
  `AdminId` int(11) NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Private` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is this group private?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_Notifications`
--

CREATE TABLE `table_Notifications` (
  `NotificationType` tinyint(1) NOT NULL COMMENT '0 = Friend Invite, 1 = Group Invite',
  `InviterId` int(11) NOT NULL,
  `InvitedId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_Scores`
--

CREATE TABLE `table_Scores` (
  `UserId` int(11) NOT NULL,
  `CandidateId` int(11) NOT NULL,
  `Score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_UserAwards`
--

CREATE TABLE `table_UserAwards` (
  `UserId` int(11) NOT NULL,
  `AwardId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_Users`
--

CREATE TABLE `table_Users` (
  `Id` int(11) NOT NULL,
  `Username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Voted` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Did the user vote?',
  `Highscore` int(11) NOT NULL DEFAULT 0,
  `UserKey` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Score` int(11) NOT NULL DEFAULT 10,
  `Friendcode` int(11) NOT NULL,
  `SeenResults` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Did the user log in this week to see the vote results?',
  `Screen` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = Green, 1 = Red',
  `Admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is the user an admin?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_UsersInGroups`
--

CREATE TABLE `table_UsersInGroups` (
  `UserId` int(11) NOT NULL,
  `Groupid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `table_Awards`
--
ALTER TABLE `table_Awards`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `table_Candidates`
--
ALTER TABLE `table_Candidates`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `table_Groups`
--
ALTER TABLE `table_Groups`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `table_Users`
--
ALTER TABLE `table_Users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `table_Awards`
--
ALTER TABLE `table_Awards`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_Candidates`
--
ALTER TABLE `table_Candidates`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_Groups`
--
ALTER TABLE `table_Groups`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_Users`
--
ALTER TABLE `table_Users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
