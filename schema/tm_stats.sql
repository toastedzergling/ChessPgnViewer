SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tm_stats`
--

-- --------------------------------------------------------

--
-- Table structure for table `chess_games`
--

CREATE TABLE IF NOT EXISTS `chess_games` (
`id` int(11) NOT NULL,
  `white_player` varchar(128) DEFAULT NULL,
  `black_player` varchar(128) DEFAULT NULL,
  `winning_player` varchar(128) DEFAULT NULL,
  `winner` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 = White; 1 = Black',
  `game_date` date DEFAULT NULL,
  `game_url` varchar(512) DEFAULT NULL,
  `pgn` text,
  `submit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=157 ;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
`id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT '"Unnamed Player"',
  `rVal` tinyint(4) unsigned NOT NULL,
  `gVal` tinyint(4) unsigned NOT NULL,
  `bVal` tinyint(4) unsigned NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE IF NOT EXISTS `records` (
`id` int(11) NOT NULL,
  `playerID` int(11) NOT NULL,
  `trackID` int(11) NOT NULL,
  `time` time NOT NULL DEFAULT '00:00:00',
  `ms` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `timeEntered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=562 ;

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE IF NOT EXISTS `tracks` (
`id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=82 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chess_games`
--
ALTER TABLE `chess_games`
 ADD PRIMARY KEY (`id`), ADD KEY `white_player` (`white_player`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `title` (`title`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chess_games`
--
ALTER TABLE `chess_games`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=157;
--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=562;
--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
