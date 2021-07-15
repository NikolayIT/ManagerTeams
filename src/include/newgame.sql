-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 03, 2009 at 11:03 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `managerteams`
--

-- --------------------------------------------------------

--
-- Table structure for table `advboards`
--

CREATE TABLE IF NOT EXISTS `advboards` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `team` mediumint(8) unsigned NOT NULL,
  `adv` tinyint(3) unsigned NOT NULL default '0',
  `board` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL,
  `left` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `team` (`team`,`adv`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Advertising boards' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `advboards`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertising`
--

CREATE TABLE IF NOT EXISTS `advertising` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `img` varchar(100) NOT NULL,
  `days` tinyint(3) unsigned NOT NULL,
  `money` int(10) unsigned NOT NULL,
  `until` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `advertising`
--

INSERT INTO `advertising` (`id`, `name`, `url`, `img`, `days`, `money`, `until`) VALUES
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 1, 2000, '2999-12-31 06:06:22'),
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 29, 75000, '2999-12-31 06:06:22'),
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 15, 15000, '2999-12-31 06:06:22'),
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 32, 65000, '2999-12-31 06:06:22'),
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 12, 12000, '2999-12-31 06:06:22'),
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 5, 15000, '2999-12-31 06:06:22'),
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 20, 29000, '2999-12-31 06:06:22'),
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 27, 65000, '2999-12-31 06:06:22'),
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 44, 100000, '2999-12-31 06:06:22'),
(NULL, 'ManagerTeams', 'http://managerteams.com', 'images/logo.png', 62, 120000, '2999-12-31 06:06:22');


-- --------------------------------------------------------

--
-- Table structure for table `advertising`
--

CREATE TABLE IF NOT EXISTS `advertising` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `img` varchar(100) NOT NULL,
  `days` tinyint(3) unsigned NOT NULL,
  `money` int(10) unsigned NOT NULL,
  `until` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `advertising`
--


-- --------------------------------------------------------

--
-- Table structure for table `bets`
--

CREATE TABLE IF NOT EXISTS `bets` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `teamid` int(9) unsigned NOT NULL,
  `matchid` int(16) unsigned NOT NULL,
  `value` int(9) unsigned NOT NULL,
  `coefic` decimal(9,2) unsigned NOT NULL,
  `result` enum('0','1','2') NOT NULL,
  `payed` enum('yes','no') NOT NULL DEFAULT 'no',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bets`
--


-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `manager` int(9) unsigned NOT NULL,
  `from` int(9) unsigned NOT NULL,
  `text` text NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `manager` (`manager`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `name`, `value`) VALUES
(NULL, 'last_update1', '2009-03-03 09:24:25'),
(NULL, 'last_update5', '2009-03-03 09:23:05'),
(NULL, 'last_update15', '2009-03-03 09:12:47'),
(NULL, 'last_update30', '2009-03-03 08:55:18'),
(NULL, 'last_update60', '2009-03-03 08:50:53'),
(NULL, 'last_update120', '2009-03-03 08:48:42'),
(NULL, 'last_update6', '2009-03-03 04:46:09'),
(NULL, 'last_update24', '2009-03-03 06:24:48'),
(NULL, 'last_update7', '2009-03-02 00:56:53'),
(NULL, 'season', '9'),
(NULL, 'match', '30'),
(NULL, 'matchcount', '42'),
(NULL, 'round', '21'),
(NULL, 'allrounds', '30'),
(NULL, 'cupround', '12'),
(NULL, 'allcuprounds', '18'),
(NULL, 'started', '2009-02-02 00:36:10'),
(NULL, 'online', '1'),
(NULL, 'offlinemessage', 'Database optimizations'),
(NULL, 'offlinestatus', '100'),
(NULL, 'cleaning', '0'),
(NULL, 'registered', '0'),
(NULL, 'onlinemanagers', '0'),
(NULL, 'fans', '0'),
(NULL, 'special_text', '<b>Welcome to ManagerTeams - the best football manager!</b>'),
(NULL, 'coders_mail', 'nrpg666@yahoo.com'),
(NULL, 'last_update4w', '2009-03-02 00:00:19'),
(NULL, 'last_update3w', '2009-02-23 00:22:14'),
(NULL, 'bets_balance', '0'),
(NULL, 'cupid', '3281'),
(NULL, 'match_of_week', ''),
(NULL, 'match_of_week2', ''),
(NULL, 'special_text_out', '<b>Welcome to ManagerTeams - the best football manager!</b>'),
(NULL, 'current_poll', '1');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `flagpic` varchar(50) NOT NULL,
  `hasnames` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `flagpic`, `hasnames`) VALUES
(1, 'Afghanistan', 'afghanistan.gif', 'no'),
(2, 'Albania', 'albania.gif', 'no'),
(3, 'Algeria', 'algeria.gif', 'no'),
(4, 'Andorra', 'andorra.gif', 'no'),
(5, 'Angola', 'angola.gif', 'no'),
(6, 'Antigua Barbuda', 'antiguabarbuda.gif', 'no'),
(7, 'Argentina', 'argentina.gif', 'no'),
(8, 'Australia', 'australia.gif', 'no'),
(9, 'Austria', 'austria.gif', 'no'),
(10, 'Bahamas', 'bahamas.gif', 'no'),
(11, 'Bangladesh', 'bangladesh.gif', 'no'),
(12, 'Barbados', 'barbados.gif', 'no'),
(13, 'Belgium', 'belgium.gif', 'no'),
(14, 'Belize', 'belize.gif', 'no'),
(15, 'Bosnia Herzegovina', 'bosniaherzegovina.gif', 'no'),
(16, 'Brazil', 'brazil.gif', 'no'),
(17, 'Bulgaria', 'bulgaria.gif', 'yes'),
(18, 'Burkina Faso', 'burkinafaso.gif', 'no'),
(19, 'Cambodia', 'cambodia.gif', 'no'),
(20, 'Canada', 'canada.gif', 'no'),
(21, 'Chile', 'chile.gif', 'no'),
(22, 'China', 'china.gif', 'no'),
(23, 'Colombia', 'colombia.gif', 'no'),
(24, 'Congo', 'congo.gif', 'no'),
(25, 'Costa Rica', 'costarica.gif', 'no'),
(26, 'Croatia', 'croatia.gif', 'no'),
(27, 'Cuba', 'cuba.gif', 'no'),
(28, 'Czech Republic', 'czechrep.gif', 'no'),
(29, 'Denmark', 'denmark.gif', 'no'),
(30, 'Dominican Republic', 'dominicanrep.gif', 'no'),
(31, 'Ecuador', 'ecuador.gif', 'no'),
(32, 'Egypt', 'egypt.gif', 'no'),
(33, 'Estonia', 'estonia.gif', 'no'),
(34, 'Finland', 'finland.gif', 'no'),
(35, 'France', 'france.gif', 'no'),
(36, 'Germany', 'germany.gif', 'yes'),
(37, 'Greece', 'greece.gif', 'no'),
(38, 'Guatemala', 'guatemala.gif', 'no'),
(39, 'Honduras', 'honduras.gif', 'no'),
(40, 'Hong Kong', 'hongkong.gif', 'no'),
(41, 'Hungary', 'hungary.gif', 'no'),
(42, 'Iceland', 'iceland.gif', 'no'),
(43, 'India', 'india.gif', 'no'),
(44, 'Ireland', 'ireland.gif', 'no'),
(45, 'Isla de Muerte', 'jollyroger.gif', 'no'),
(46, 'Israel', 'israel.gif', 'no'),
(47, 'Italy', 'italy.gif', 'no'),
(48, 'Jamaica', 'jamaica.gif', 'no'),
(49, 'Japan', 'japan.gif', 'no'),
(50, 'Kiribati', 'kiribati.gif', 'no'),
(51, 'Kyrgyzstan', 'kyrgyzstan.gif', 'no'),
(52, 'Laos', 'laos.gif', 'no'),
(53, 'Latvia', 'latvia.gif', 'no'),
(54, 'Lebanon', 'lebanon.gif', 'no'),
(55, 'Lithuania', 'lithuania.gif', 'no'),
(56, 'Luxembourg', 'luxembourg.gif', 'no'),
(57, 'Malaysia', 'malaysia.gif', 'no'),
(58, 'Mexico', 'mexico.gif', 'no'),
(59, 'Nauru', 'nauru.gif', 'no'),
(60, 'Netherlands Antilles', 'nethantilles.gif', 'no'),
(61, 'Netherlands', 'netherlands.gif', 'no'),
(62, 'New Zealand', 'newzealand.gif', 'no'),
(63, 'Nigeria', 'nigeria.gif', 'no'),
(64, 'North Korea', 'northkorea.gif', 'no'),
(65, 'Norway', 'norway.gif', 'no'),
(66, 'Pakistan', 'pakistan.gif', 'no'),
(67, 'Paraguay', 'paraguay.gif', 'no'),
(68, 'Peru', 'peru.gif', 'no'),
(69, 'Philippines', 'philippines.gif', 'no'),
(70, 'Poland', 'poland.gif', 'no'),
(71, 'Portugal', 'portugal.gif', 'no'),
(72, 'Puerto Rico', 'puertorico.gif', 'no'),
(73, 'Romania', 'romania.gif', 'no'),
(74, 'Russia', 'russia.gif', 'no'),
(75, 'Senegal', 'senegal.gif', 'no'),
(76, 'Serbia', 'serbia.gif', 'no'),
(77, 'Seychelles', 'seychelles.gif', 'no'),
(78, 'Singapore', 'singapore.gif', 'no'),
(79, 'Slovenia', 'slovenia.gif', 'no'),
(80, 'South Africa', 'southafrica.gif', 'no'),
(81, 'South Korea', 'southkorea.gif', 'no'),
(82, 'Spain', 'spain.gif', 'no'),
(83, 'Sweden', 'sweden.gif', 'no'),
(84, 'Switzerland', 'switzerland.gif', 'no'),
(85, 'Taiwan', 'taiwan.gif', 'no'),
(86, 'Thailand', 'thailand.gif', 'no'),
(87, 'Togo', 'togo.gif', 'no'),
(88, 'Trinidad & Tobago', 'trinidadandtobago.gif', 'no'),
(89, 'Turkey', 'turkey.gif', 'no'),
(90, 'Turkmenistan', 'turkmenistan.gif', 'no'),
(91, 'USA', 'usa.gif', 'no'),
(92, 'Ukraine', 'ukraine.gif', 'no'),
(93, 'United Kingdom', 'uk.gif', 'no'),
(94, 'Uruguay', 'uruguay.gif', 'no'),
(95, 'Uzbekistan', 'uzbekistan.gif', 'no'),
(96, 'Vanuatu', 'vanuatu.gif', 'no'),
(97, 'Venezuela', 'venezuela.gif', 'no'),
(98, 'Vietnam', 'vietnam.gif', 'no'),
(99, 'Western Samoa', 'westernsamoa.gif', 'no'),
(100, 'England', 'england.gif', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `friendly_invitations`
--

CREATE TABLE IF NOT EXISTS `friendly_invitations` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `fromteam` int(9) unsigned NOT NULL,
  `toteam` int(9) unsigned NOT NULL,
  `date` date NOT NULL,
  `time` enum('0','1','2','3') NOT NULL,
  `type` enum('home','away') NOT NULL DEFAULT 'home',
  `accepted` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `fromteam` (`fromteam`),
  KEY `toteam` (`toteam`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `friendly_invitations`
--


-- --------------------------------------------------------

--
-- Table structure for table `friendly_participants`
--

CREATE TABLE IF NOT EXISTS `friendly_participants` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(9) unsigned NOT NULL,
  `cupstart` int(2) NOT NULL DEFAULT '0',
  `incup` enum('yes','no') NOT NULL DEFAULT 'yes',
  `team` int(9) unsigned NOT NULL,
  `total` int(2) unsigned NOT NULL DEFAULT '0',
  `points` int(2) unsigned NOT NULL DEFAULT '0',
  `wins` int(6) unsigned NOT NULL DEFAULT '0',
  `draws` int(6) unsigned NOT NULL DEFAULT '0',
  `loses` int(6) unsigned NOT NULL DEFAULT '0',
  `goalsscored` int(9) unsigned NOT NULL DEFAULT '0',
  `goalsconceded` int(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`team`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `friendly_participants`
--


-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user1` int(9) unsigned NOT NULL,
  `user2` int(9) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user1` (`user1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `friends`
--


-- --------------------------------------------------------

--
-- Table structure for table `invitetries`
--

CREATE TABLE IF NOT EXISTS `invitetries` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `userid` int(9) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `fromname` varchar(32) NOT NULL,
  `frommail` varchar(32) NOT NULL,
  `toname` varchar(32) NOT NULL,
  `tomail` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tomail` (`tomail`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `invitetries`
--


-- --------------------------------------------------------

--
-- Table structure for table `ips`
--

CREATE TABLE IF NOT EXISTS `ips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) NOT NULL,
  `iplong` int(12) NOT NULL,
  `trace` text NOT NULL,
  `lastinfo` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `banned` enum('yes','no') NOT NULL DEFAULT 'no',
  `banreason` text NOT NULL,
  `host` text NOT NULL,
  `REMOTE_ADDR` text NOT NULL,
  `HTTP_VIA` text NOT NULL,
  `HTTP_X_FORWARDED` text NOT NULL,
  `HTTP_X_FORWARDED_FOR` text NOT NULL,
  `HTTP_FORWARDED` text NOT NULL,
  `HTTP_FORWARDED_FOR` text NOT NULL,
  `HTTP_COMING_FROM` text NOT NULL,
  `HTTP_X_COMING_FROM` text NOT NULL,
  `vote` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `voted` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ips`
--


-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `file` varchar(25) NOT NULL,
  `file_match` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `file`, `file_match`) VALUES
(1, 'English', 'english.php', 'english_match.php'),
(2, 'Bulgarian', 'bulgarian.php', 'bulgarian_match.php'),
(3, 'Spanish', 'spanish.php', 'spanish_match.php');

-- --------------------------------------------------------

--
-- Table structure for table `league_history`
--

CREATE TABLE IF NOT EXISTS `league_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventtime` datetime NOT NULL,
  `league` int(9) unsigned NOT NULL,
  `season` int(3) unsigned NOT NULL,
  `event` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `league` (`league`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `league_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE IF NOT EXISTS `loans` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `team` int(9) unsigned NOT NULL,
  `money` int(12) unsigned NOT NULL,
  `part` int(3) NOT NULL,
  `parts` int(3) NOT NULL,
  `payed` enum('yes','no') NOT NULL DEFAULT 'no',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team` (`team`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `loans`
--


-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `ip` int(10) unsigned NOT NULL,
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `page` text NOT NULL,
  `referer` text NOT NULL,
  `postdata` text NOT NULL,
  `uid` int(7) unsigned NOT NULL DEFAULT '0',
  `browser` text NOT NULL,
  `os` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `logs`
--


-- --------------------------------------------------------

--
-- Table structure for table `manager_history`
--

CREATE TABLE IF NOT EXISTS `manager_history` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `eventtime` datetime NOT NULL,
  `manager` int(9) unsigned NOT NULL,
  `season` int(3) unsigned NOT NULL,
  `event` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `manager` (`manager`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `manager_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `match`
--

CREATE TABLE IF NOT EXISTS `match` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) unsigned NOT NULL,
  `season` tinyint(3) unsigned NOT NULL,
  `round` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `start` datetime NOT NULL,
  `hometeam` int(9) unsigned NOT NULL,
  `awayteam` int(9) unsigned NOT NULL,
  `hometactic` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `awaytactic` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `homescore` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `awayscore` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `played` enum('yes','no') NOT NULL DEFAULT 'no',
  `rules` enum('league','cup','frmatch','frcup','frleague') NOT NULL DEFAULT 'league',
  `odds` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `better` enum('0','1','2') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `round` (`round`),
  KEY `hometeam` (`hometeam`),
  KEY `awayteam` (`awayteam`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Matches' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `match`
--


-- --------------------------------------------------------

--
-- Table structure for table `match_comments`
--

CREATE TABLE IF NOT EXISTS `match_comments` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `match` int(16) unsigned NOT NULL DEFAULT '0',
  `from` int(9) unsigned NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `match` (`match`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `match_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `match_type`
--

CREATE TABLE IF NOT EXISTS `match_type` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `createdby` int(9) unsigned NOT NULL DEFAULT '0',
  `startat` int(2) unsigned NOT NULL DEFAULT '0',
  `finished` enum('yes','no') NOT NULL DEFAULT 'no',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fee` int(9) unsigned NOT NULL DEFAULT '0',
  `participants` int(6) unsigned NOT NULL DEFAULT '16',
  `teams` enum('4','8','16','32','64','128','256','512','1024','all') NOT NULL DEFAULT '16',
  `type` enum('League','Cup','Friendly cup','Friendly league','Special cup') NOT NULL DEFAULT 'League',
  `started` enum('yes','no') NOT NULL DEFAULT 'yes',
  `password` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `match_type`
--


-- --------------------------------------------------------

--
-- Table structure for table `match_type_comments`
--

CREATE TABLE IF NOT EXISTS `match_type_comments` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `match_type` int(9) unsigned NOT NULL DEFAULT '0',
  `from` int(9) unsigned NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `match_type` (`match_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `match_type_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `timesent` datetime NOT NULL,
  `fromid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `fromname` varchar(16) NOT NULL,
  `toid` mediumint(8) unsigned NOT NULL,
  `toname` varchar(16) NOT NULL,
  `caption` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `readstatus` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `fromid` (`fromid`),
  KEY `toid` (`toid`),
  KEY `readstatus` (`readstatus`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `money_history`
--

CREATE TABLE IF NOT EXISTS `money_history` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `eventtime` datetime NOT NULL,
  `team` mediumint(8) unsigned NOT NULL,
  `season` tinyint(3) unsigned NOT NULL,
  `event` varchar(200) NOT NULL,
  `money` int(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team` (`team`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `money_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `added` datetime NOT NULL,
  `addedby` varchar(16) NOT NULL DEFAULT 'unknown',
  `caption` varchar(32) NOT NULL,
  `short` varchar(250) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `news`
--


-- --------------------------------------------------------

--
-- Table structure for table `paytries`
--

CREATE TABLE IF NOT EXISTS `paytries` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `ip` varchar(40) CHARACTER SET latin1 NOT NULL,
  `user` int(6) NOT NULL,
  `text` text CHARACTER SET latin1 NOT NULL,
  `success` int(1) NOT NULL,
  `type` enum('vip','money') CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `paytries`
--


-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `shortname` varchar(30) NOT NULL,
  `team` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `number` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `contrtime` tinyint(3) unsigned NOT NULL DEFAULT '60',
  `wage` mediumint(8) unsigned NOT NULL DEFAULT '300',
  `winbonus` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `car` enum('yes','no') NOT NULL DEFAULT 'no',
  `house` enum('yes','no') NOT NULL DEFAULT 'no',
  `country` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `age` tinyint(3) unsigned NOT NULL DEFAULT '17',
  `weight` tinyint(3) unsigned NOT NULL DEFAULT '75',
  `height` tinyint(3) unsigned NOT NULL DEFAULT '175',
  `possition` enum('GK','LB','CB','RB','LBM','CBM','RBM','LM','CM','RM','LFM','CFM','RFM','LF','CF','RF') NOT NULL DEFAULT 'GK',
  `training` tinyint(3) unsigned NOT NULL DEFAULT '8',
  `injured` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `healed` enum('yes','no') NOT NULL DEFAULT 'no',
  `banleague` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bancup` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `global` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `currentform` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bestform` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `aggression` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `experience` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fitness` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `stamina` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `speed` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `ballcontrol` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `passing` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `goalkeeping` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `takeball` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `playalong` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `jumping` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `flexibility` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `courage` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `positioning` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `tackling` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `heading` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `playitout` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `dribble` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `shooting` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `technique` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `goalsense` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `picture` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team` (`team`),
  KEY `possition` (`possition`),
  KEY `contrtime` (`contrtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `players`
--


-- --------------------------------------------------------

--
-- Table structure for table `players_notes`
--

CREATE TABLE IF NOT EXISTS `players_notes` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `player` int(12) unsigned NOT NULL,
  `text` text NOT NULL,
  `fromid` int(9) unsigned NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player` (`player`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `players_notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `players_stats`
--

CREATE TABLE IF NOT EXISTS `players_stats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cur_leag_goals` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cur_leag_red` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_leag_yellow` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_leag_played` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_leag_inj` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_cup_goals` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cur_cup_red` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_cup_yellow` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_cup_played` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_cup_inj` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_fr_goals` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cur_fr_red` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_fr_yellow` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_fr_played` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cur_fr_inj` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `all_leag_goals` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_leag_red` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_leag_yellow` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_leag_played` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_leag_inj` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_cup_goals` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_cup_red` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_cup_yellow` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_cup_played` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_cup_inj` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_fr_goals` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_fr_red` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_fr_yellow` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_fr_played` smallint(5) unsigned NOT NULL DEFAULT '0',
  `all_fr_inj` smallint(5) unsigned NOT NULL DEFAULT '0',
  `league` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `league` (`league`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `players_stats`
--


-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `option_1` text NOT NULL,
  `option_2` text NOT NULL,
  `option_3` text NOT NULL,
  `option_4` text NOT NULL,
  `option_5` text NOT NULL,
  `option_6` text NOT NULL,
  `option_7` text NOT NULL,
  `option_8` text NOT NULL,
  `option_9` text NOT NULL,
  `option_10` text NOT NULL,
  `option_11` text NOT NULL,
  `option_12` text NOT NULL,
  `option_13` text NOT NULL,
  `option_14` text NOT NULL,
  `option_15` text NOT NULL,
  `option_16` text NOT NULL,
  `option_17` text NOT NULL,
  `option_18` text NOT NULL,
  `option_19` text NOT NULL,
  `option_20` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `polls`
--


-- --------------------------------------------------------

--
-- Table structure for table `poll_votes`
--

CREATE TABLE IF NOT EXISTS `poll_votes` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `poll` int(6) unsigned NOT NULL,
  `user` int(9) unsigned NOT NULL,
  `option` int(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `poll` (`poll`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `poll_votes`
--


-- --------------------------------------------------------

--
-- Table structure for table `shortlist`
--

CREATE TABLE IF NOT EXISTS `shortlist` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(12) unsigned NOT NULL,
  `player` int(12) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shortlist`
--


-- --------------------------------------------------------

--
-- Table structure for table `stadiums`
--

CREATE TABLE IF NOT EXISTS `stadiums` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `eastseats` int(1) unsigned NOT NULL DEFAULT '1',
  `westseats` int(1) unsigned NOT NULL DEFAULT '1',
  `northseats` int(1) unsigned NOT NULL DEFAULT '1',
  `southseats` int(1) unsigned NOT NULL DEFAULT '1',
  `vipseats` int(1) unsigned NOT NULL DEFAULT '0',
  `parking` int(1) unsigned NOT NULL DEFAULT '0',
  `bars` int(1) unsigned NOT NULL DEFAULT '0',
  `toilets` int(1) unsigned NOT NULL DEFAULT '0',
  `grass` int(1) unsigned NOT NULL DEFAULT '1',
  `lights` int(1) unsigned NOT NULL DEFAULT '1',
  `boards` int(1) unsigned NOT NULL DEFAULT '1',
  `youthcenter` int(1) unsigned NOT NULL DEFAULT '0',
  `roof` int(1) unsigned NOT NULL DEFAULT '0',
  `heater` int(1) unsigned NOT NULL DEFAULT '0',
  `sprinkler` int(1) unsigned NOT NULL DEFAULT '0',
  `fanshop` int(1) unsigned NOT NULL DEFAULT '0',
  `hospital` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `stadiums`
--


-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `type` enum('coach','scout','doctor') NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `age` tinyint(3) unsigned NOT NULL DEFAULT '40',
  `team` mediumint(9) NOT NULL,
  `contrtime` tinyint(4) NOT NULL,
  `atcourse` enum('yes','no') NOT NULL DEFAULT 'no',
  `courseuntil` datetime NOT NULL,
  `wage` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `team` (`team`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `staff`
--


-- --------------------------------------------------------

--
-- Table structure for table `tactics`
--

CREATE TABLE IF NOT EXISTS `tactics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `formation` int(2) unsigned NOT NULL DEFAULT '4',
  `aggression` int(3) unsigned NOT NULL DEFAULT '50',
  `style` int(3) unsigned NOT NULL DEFAULT '50',
  `tactics` int(2) unsigned NOT NULL DEFAULT '0',
  `captain` int(9) unsigned NOT NULL DEFAULT '0',
  `GK` int(9) unsigned NOT NULL DEFAULT '0',
  `LB` int(9) unsigned NOT NULL DEFAULT '0',
  `CB1` int(9) unsigned NOT NULL DEFAULT '0',
  `CB2` int(9) unsigned NOT NULL DEFAULT '0',
  `CB3` int(9) unsigned NOT NULL DEFAULT '0',
  `RB` int(9) unsigned NOT NULL DEFAULT '0',
  `LM` int(9) unsigned NOT NULL DEFAULT '0',
  `CM1` int(9) unsigned NOT NULL DEFAULT '0',
  `CM2` int(9) unsigned NOT NULL DEFAULT '0',
  `CM3` int(9) unsigned NOT NULL DEFAULT '0',
  `RM` int(9) unsigned NOT NULL DEFAULT '0',
  `CF1` int(9) unsigned NOT NULL DEFAULT '0',
  `CF2` int(9) unsigned NOT NULL DEFAULT '0',
  `CF3` int(9) unsigned NOT NULL DEFAULT '0',
  `S1` int(9) unsigned NOT NULL DEFAULT '0',
  `S2` int(9) unsigned NOT NULL DEFAULT '0',
  `S3` int(9) unsigned NOT NULL DEFAULT '0',
  `S4` int(9) unsigned NOT NULL DEFAULT '0',
  `S5` int(9) unsigned NOT NULL DEFAULT '0',
  `GK_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `LB_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `CB1_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `CB2_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `CB3_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `RB_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `LM_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `CM1_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `CM2_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `CM3_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `RM_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `CF1_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `CF2_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `CF3_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `S1_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `S2_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `S3_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `S4_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `S5_ind` int(2) unsigned NOT NULL DEFAULT '0',
  `sub1_min` int(2) unsigned NOT NULL DEFAULT '0',
  `sub1_out` int(9) unsigned NOT NULL DEFAULT '0',
  `sub1_in` int(9) unsigned NOT NULL DEFAULT '0',
  `sub2_min` int(2) unsigned NOT NULL DEFAULT '0',
  `sub2_out` int(9) unsigned NOT NULL DEFAULT '0',
  `sub2_in` int(9) unsigned NOT NULL DEFAULT '0',
  `sub3_min` int(2) unsigned NOT NULL DEFAULT '0',
  `sub3_out` int(9) unsigned NOT NULL DEFAULT '0',
  `sub3_in` int(9) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tactics`
--


-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `free` enum('yes','no') NOT NULL DEFAULT 'yes',
  `stadium` int(9) unsigned NOT NULL DEFAULT '0',
  `league` char(10) NOT NULL,
  `cup` enum('yes','no') NOT NULL DEFAULT 'yes',
  `money` int(12) NOT NULL DEFAULT '2000000',
  `total` int(2) unsigned NOT NULL DEFAULT '0',
  `points` int(2) unsigned NOT NULL DEFAULT '0',
  `wins` int(6) unsigned NOT NULL DEFAULT '0',
  `draws` int(6) unsigned NOT NULL DEFAULT '0',
  `loses` int(6) unsigned NOT NULL DEFAULT '0',
  `goalsscored` int(9) NOT NULL DEFAULT '0',
  `goalsconceded` int(9) NOT NULL DEFAULT '0',
  `teamspirit` int(3) unsigned NOT NULL DEFAULT '50',
  `fanbase` int(9) unsigned NOT NULL DEFAULT '1000',
  `fansatisfaction` int(3) unsigned NOT NULL DEFAULT '50',
  `global` int(3) unsigned NOT NULL DEFAULT '0',
  `hometshirt` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `homeshorts` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `homesocks` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `awaytshirt` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `awayshorts` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `awaysocks` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `tactic1` int(10) unsigned NOT NULL DEFAULT '0',
  `tactic2` int(10) unsigned NOT NULL DEFAULT '0',
  `tactic3` int(10) unsigned NOT NULL DEFAULT '0',
  `tactic4` int(10) unsigned NOT NULL DEFAULT '0',
  `tactic5` int(10) unsigned NOT NULL DEFAULT '0',
  `odds_points` int(9) unsigned NOT NULL DEFAULT '0',
  `odds_matches` int(9) unsigned NOT NULL DEFAULT '0',
  `odds_balance` double(16,2) NOT NULL DEFAULT '0.00',
  `daysminus` int(3) unsigned NOT NULL DEFAULT '0',
  `vote1` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vote2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bank_in` int(12) NOT NULL DEFAULT '0',
  `bank_out` int(12) NOT NULL DEFAULT '0',
  `bank_until` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `teams`
--


-- --------------------------------------------------------

--
-- Table structure for table `team_history`
--

CREATE TABLE IF NOT EXISTS `team_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventtime` datetime NOT NULL,
  `team` mediumint(8) unsigned NOT NULL,
  `season` tinyint(3) unsigned NOT NULL,
  `event` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team` (`team`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `team_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE IF NOT EXISTS `transfers` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `player` int(12) unsigned NOT NULL,
  `fromteam` int(9) unsigned NOT NULL DEFAULT '0',
  `bestoffer` bigint(20) unsigned NOT NULL,
  `offerteam` int(9) unsigned NOT NULL DEFAULT '0',
  `until` datetime NOT NULL,
  `signbonus` int(6) unsigned NOT NULL DEFAULT '0',
  `contrtime` int(2) unsigned NOT NULL DEFAULT '60',
  `wage` int(6) unsigned NOT NULL DEFAULT '300',
  `winbonus` int(6) unsigned NOT NULL DEFAULT '0',
  `car` enum('yes','no') NOT NULL DEFAULT 'no',
  `house` enum('yes','no') NOT NULL DEFAULT 'no',
  `available` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  UNIQUE KEY `player` (`player`),
  KEY `fromteam` (`fromteam`),
  KEY `offerteam` (`offerteam`),
  KEY `available` (`available`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `transfers`
--


-- --------------------------------------------------------

--
-- Table structure for table `transfer_reports`
--

CREATE TABLE IF NOT EXISTS `transfer_reports` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `transfer` int(9) unsigned NOT NULL DEFAULT '0',
  `from` int(9) unsigned NOT NULL DEFAULT '0',
  `reason` text NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `transfer_reports`
--


-- --------------------------------------------------------

--
-- Table structure for table `trophies`
--

CREATE TABLE IF NOT EXISTS `trophies` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `team` int(9) unsigned NOT NULL,
  `type` enum('cup','league_A','league_B','league_C','league_D','league_E','league_F','league_G','league_H','friendly') NOT NULL,
  `name` varchar(32) NOT NULL,
  `season` int(6) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `team` (`team`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `trophies`
--


-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `table` varchar(200) NOT NULL,
  `field` varchar(200) NOT NULL,
  `type` enum('+','-','=') NOT NULL DEFAULT '+',
  `value` varchar(200) NOT NULL DEFAULT '1',
  `whereid` int(12) unsigned NOT NULL,
  `until` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `until` (`until`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `updates`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL,
  `passhash` varchar(32) NOT NULL,
  `newpass` varchar(32) NOT NULL,
  `secret` varchar(16) NOT NULL,
  `invitedby` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `registred` datetime NOT NULL,
  `lastlogin` datetime NOT NULL,
  `lastaction` datetime NOT NULL,
  `lastactionin` text NOT NULL,
  `realname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `class` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL,
  `team` mediumint(8) unsigned NOT NULL,
  `country` tinyint(3) unsigned NOT NULL,
  `language` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `wins` smallint(5) unsigned NOT NULL DEFAULT '0',
  `draws` smallint(5) unsigned NOT NULL DEFAULT '0',
  `loses` smallint(5) unsigned NOT NULL DEFAULT '0',
  `points` smallint(5) unsigned NOT NULL DEFAULT '0',
  `weekpoints` smallint(5) unsigned NOT NULL DEFAULT '0',
  `goalsscored` int(9) unsigned NOT NULL DEFAULT '0',
  `goalsconceded` int(9) unsigned NOT NULL DEFAULT '0',
  `sex` enum('unknown','Male','Female') NOT NULL DEFAULT 'unknown',
  `favteam` varchar(50) NOT NULL DEFAULT 'unknown',
  `site` varchar(100) NOT NULL DEFAULT 'index.php',
  `avatar` varchar(100) NOT NULL DEFAULT 'images/logo.png',
  `owntext` varchar(200) NOT NULL DEFAULT '[b]I love this game![/b]',
  `contlang` varchar(50) NOT NULL DEFAULT 'unknown',
  `showmail` enum('yes','no') NOT NULL DEFAULT 'yes',
  `mailreports` enum('yes','no') NOT NULL DEFAULT 'yes',
  `holiday` enum('yes','no') NOT NULL DEFAULT 'no',
  `vipuntil` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `team` (`team`),
  KEY `id` (`id`,`passhash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `passhash`, `newpass`, `secret`, `invitedby`, `registred`, `lastlogin`, `lastaction`, `lastactionin`, `realname`, `email`, `class`, `ip`, `team`, `country`, `language`, `wins`, `draws`, `loses`, `points`, `weekpoints`, `goalsscored`, `goalsconceded`, `sex`, `favteam`, `site`, `avatar`, `owntext`, `contlang`, `showmail`, `mailreports`, `holiday`, `vipuntil`) VALUES
(NULL, 'admin', '9848655f21cb22f927d9803a34527866', '', '70549ad0fe15067f', 0, NOW(), NOW(), NOW(), '/index.php', 'Administrator', 'admin@admin.com', 9, '91.148.145.91', 31575, 17, 1, 0, 0, 0, 0, 0, 0, 0, 'unknown', 'unknown', 'index.php', 'images/logo.png', '[b]I love this game![/b]', 'unknown', 'yes', 'yes', 'no', '0000-00-00 00:00:00');
