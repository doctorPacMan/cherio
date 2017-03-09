<?
$query = "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+06:00';
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL DEFAULT '123qwe',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ssid` varchar(32) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `login_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
?>