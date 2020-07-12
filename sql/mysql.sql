CREATE TABLE `rmms_categos` (
  `id_cat` int(11) NOT NULL auto_increment,
  `nombre` varchar(150) NOT NULL default '',
  `desc` text NOT NULL,
  `img` varchar(255) NOT NULL default '',
  `imgtype` smallint(1) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id_cat`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE `rmms_productos` (
  `id_prd` int(11) NOT NULL auto_increment,
  `nombre` varchar(150) NOT NULL default '',
  `shortdesc` varchar(255) NOT NULL default '',
  `longdesc` text NOT NULL,
  `codigo` varchar(50) NOT NULL default '',
  `cantidad` int(11) NOT NULL default '0',
  `medidas` varchar(100) NOT NULL default '',
  `peso` varchar(100) NOT NULL default '',
  `minimo` int(11) NOT NULL default '0',
  `precio` double NOT NULL default '0',
  `img` varchar(255) NOT NULL default '',
  `imgtype` smallint(1) NOT NULL default '0',
  `inblock` smallint(1) NOT NULL default '0',
  `id_cat` int (11) NOT NULL default '0',
  PRIMARY KEY  (`id_prd`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE `rmms_images` (
`id_img` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`idp` INT NOT NULL ,
`titulo` VARCHAR( 255 ) NOT NULL ,
`img` VARCHAR( 255 ) NOT NULL
) TYPE=MYISAM ;
       