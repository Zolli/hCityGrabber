CREATE TABLE IF NOT EXISTS `city` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `county_id` int(2) NOT NULL,
  `population` int(8) NOT NULL,
  `postal_code` int(5) NOT NULL,
  `phone_prefix` int(3) NOT NULL,
  `population_density` int(5) NOT NULL,
  `area` int(10) NOT NULL,
  `latitude` varchar(32) NOT NULL,
  `longitude` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `postal_code` (`postal_code`),
  KEY `coord` (`latitude`,`longitude`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `county` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;