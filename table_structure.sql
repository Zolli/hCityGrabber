CREATE TABLE IF NOT EXISTS `city` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Egyedi azonosító',
  `name` varchar(255) NOT NULL COMMENT 'A település neve',
  `county_id` int(2) NOT NULL COMMENT 'A megye azonosítója',
  `population` int(8) NOT NULL COMMENT 'Település lakosainak száma',
  `postal_code` int(5) NOT NULL COMMENT 'Postai irányítószám',
  `phone_prefix` int(3) NOT NULL COMMENT 'Telefon előhívószám',
  `population_density` double NOT NULL COMMENT 'Népsűrűség',
  `area` double NOT NULL COMMENT 'Terület (km2-ben)',
  `latitude` double NOT NULL COMMENT 'Szélesség',
  `longitude` double NOT NULL COMMENT 'Hosszúság',
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