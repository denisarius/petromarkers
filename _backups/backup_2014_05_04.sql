--
-- Table structure for table '_files_data'
--

DROP TABLE IF EXISTS _files_data;
CREATE TABLE `_files_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` text,
  `size` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `md5` varchar(32) DEFAULT NULL,
  `content` mediumblob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table '_temp_files'
--

DROP TABLE IF EXISTS _temp_files;
CREATE TABLE `_temp_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(254) DEFAULT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=cp1251;

insert into _temp_files (`id`, `file`, `created`) values ('1', 'objects', '2014-05-04'),('2', 'objects', '2014-05-04'),('3', 'bcff20b5cfbd8edd0.jpg', '2014-05-04'),('4', 'objects', '2014-05-04'),('5', 'objects', '2014-05-04'),('6', 'objects', '2014-05-04'),('7', '4343a2959a980bc40.png', '2014-05-04'),('8', '130235cdbdf961b50.png', '2014-05-04'),('9', 'objects', '2014-05-04'),('10', '89f15b208c1792240.png', '2014-05-04'),('11', '32c5da6fc7c3d5170.png', '2014-05-04'),('12', '690d9d65618eaeb90.png', '2014-05-04'),('13', '2bc2556e0e54df8c0.png', '2014-05-04'),('14', '3a756bccf20e22560.png', '2014-05-04'),('15', '52577dbe18db8dfb0.png', '2014-05-04'),('16', 'ee371373b7ed97ee0.png', '2014-05-04'),('17', 'e9723df19e73be740.png', '2014-05-04'),('18', '2947b87afcf3621d0.png', '2014-05-04'),('19', 'a1c9f5237624ed750.png', '2014-05-04'),('20', '2947b87afcf3621d0.png', '2014-05-04'),('21', '6f2dd5207d2696160.png', '2014-05-04'),('22', '8febd49980f705c20.png', '2014-05-04'),('23', '1cc3430d2148e52b0.png', '2014-05-04'),('24', '25b326a24c8b793d0.png', '2014-05-04'),('25', 'af582992260777290.png', '2014-05-04');

--
-- Table structure for table '_vfs'
--

DROP TABLE IF EXISTS _vfs;
CREATE TABLE `_vfs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `real_name` varchar(64) DEFAULT NULL,
  `real_path` text,
  `virtual_name` varchar(64) DEFAULT NULL,
  `virtual_path` text,
  PRIMARY KEY (`id`),
  KEY `virtual_name` (`virtual_name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'constants'
--

DROP TABLE IF EXISTS constants;
CREATE TABLE `constants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'menus'
--

DROP TABLE IF EXISTS menus;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251;

insert into menus (`id`, `name`) values ('1', 'Основной раздел');

--
-- Table structure for table 'menus_items'
--

DROP TABLE IF EXISTS menus_items;
CREATE TABLE `menus_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) DEFAULT NULL,
  `url` text,
  `parent` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `menu` int(11) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  `tag` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251;

insert into menus_items (`id`, `name`, `url`, `parent`, `sort`, `menu`, `visible`, `tag`) values ('1', 'О проекте', '/', '0', '1', '1', '1', ''),('2', 'Культурные объекты', '', '0', '2', '1', '1', ''),('3', 'Контакты', '', '0', '3', '1', '1', '');

--
-- Table structure for table 'objects'
--

DROP TABLE IF EXISTS objects;
CREATE TABLE `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_item` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(254) DEFAULT NULL,
  `note` text,
  `image` varchar(254) DEFAULT NULL,
  `gallery` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `date` date DEFAULT NULL,
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `ft_name` (`name`,`note`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=cp1251;

insert into objects (`id`, `menu_item`, `type`, `name`, `note`, `image`, `gallery`, `sort`, `date`, `visible`) values ('1', '2', '1', 'Петрозаводск', '<p>\n	Тут перечислены объекты, расположенные в г. Петрозаводски и его ближайших окрестностях</p>\n', 'aab67a0252bd4cc50.png', '0', '0', '2014-05-04', '0'),('2', '2', '1', 'Кижи', '<p>\n	Памятники деревянного зодчества на о. Кижи</p>\n', '1991dc2cf6f2d9350.png', '0', '0', '2014-05-04', '0'),('3', '2', '2', 'Собор Александра Невского', '<p>\n	Государственный совет республики Крым на сегодняшней сессии принял постановление о создании Банка Крыма, сообщает &laquo;Крыминформ&raquo;. Решение было принято единогласно. Ранее президент Владимир Путин подписал закон об особенностях функционирования финансовой системы Крыма на переходный период.</p>\n', '380bb2479a337d9a0.jpg', '0', '0', '2014-05-04', '0'),('4', '2', '1', 'Кластер 3', '', '05b46590e93bcbde0.png', '0', '0', '2014-05-04', '0'),('5', '2', '1', 'Кластер 4', '', '48ef0b56d988f11d0.png', '0', '0', '2014-05-04', '0'),('6', '2', '1', 'Кластер 5', '', '6835fe6df882c3cf0.png', '0', '0', '2014-05-04', '0');

--
-- Table structure for table 'objects_details'
--

DROP TABLE IF EXISTS objects_details;
CREATE TABLE `objects_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node` int(11) NOT NULL DEFAULT '0',
  `typeId` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=cp1251;

insert into objects_details (`id`, `node`, `typeId`, `type`, `value`) values ('4', '3', 'address', 's', 'пр. Александра Неского, 32'),('3', '3', 'cluster', 'oo', '1');

--
-- Table structure for table 'text_parts'
--

DROP TABLE IF EXISTS text_parts;
CREATE TABLE `text_parts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0',
  `node` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `title` varchar(254) DEFAULT NULL,
  `image` varchar(50) DEFAULT NULL,
  `content` longtext,
  `sort` int(11) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tnvs` (`type`,`node`,`visible`,`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


--
-- Table structure for table 'texts'
--

DROP TABLE IF EXISTS texts;
CREATE TABLE `texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `signature` varchar(10) DEFAULT NULL,
  `menu_item` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `title` varchar(254) DEFAULT NULL,
  `keywords` text,
  `descr` text,
  `content` longtext,
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


