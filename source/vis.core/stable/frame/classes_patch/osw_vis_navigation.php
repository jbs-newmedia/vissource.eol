<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

# current version build
$cv=$this->_version;
$cb=$this->_build;


#============================================================================================================================
# check Tabelle#1
#============================================================================================================================
$table='vis_navigation';
$QreadData=osW_Database::getInstance()->query('SHOW TABLE STATUS FROM :database_db: LIKE :table:');
$QreadData->bindRaw(':database_db:', vOut('database_db'));
$QreadData->bindValue(':table:', vOut('database_prefix').$table);
$QreadData->execute();
if ($QreadData->numberOfRows()==1) {
	$QreadData->next();
	$avb_tbl=$QreadData->result['Comment'];
} else {
	$avb_tbl='0.0';
}

$avb_tbl=explode('.', $avb_tbl);
if (count($avb_tbl)==1) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=0;
} elseif (count($avb_tbl)==2) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=intval($avb_tbl[1]);
} else {
	$av_tbl=0;
	$ab_tbl=0;
}

if (($av_tbl==0)&&($ab_tbl==0)) {
	$QwriteData=osW_Database::getInstance()->query('
CREATE TABLE :table: (
  navigation_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  tool_id int(11) NOT NULL,
  navigation_group_id int(11) NOT NULL,
  navigation_page_id int(11) NOT NULL,
  navigation_page varchar(32) NOT NULL,
  navigation_title varchar(64) NOT NULL,
  navigation_sortorder int(11) unsigned NOT NULL,
  navigation_permissionflags varchar(256) NOT NULL,
  navigation_ispublic tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (navigation_id),
  KEY tool_id (tool_id),
  KEY navigation_group_id (navigation_group_id),
  KEY navigation_page_id (navigation_page_id),
  KEY navigation_sortorder (navigation_sortorder),
  KEY navigation_ispublic (navigation_ispublic)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<2)) {
	$QwriteData=osW_Database::getInstance()->query('DROP TABLE :table:');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

$QwriteData=osW_Database::getInstance()->query('
CREATE TABLE :table: (
  navigation_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  tool_id int(10) unsigned NOT NULL,
  navigation_parent_id int(11) unsigned NOT NULL,
  navigation_title varchar(128) NOT NULL,
  page_id int(11) unsigned NOT NULL,
  navigation_sortorder int(11) unsigned NOT NULL,
  navigation_ispublic int(11) unsigned NOT NULL,
  PRIMARY KEY (navigation_id),
  KEY navigation_parent_id (navigation_parent_id),
  KEY navigation_sortorder (navigation_sortorder),
  KEY navigation_ispublic (navigation_ispublic),
  KEY tool_id (tool_id),
  KEY page_id (page_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'2.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=2;
	$ab_tbl=0;
}

if (($av_tbl<=2)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD navigation_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( navigation_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD navigation_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( navigation_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD navigation_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( navigation_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD navigation_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( navigation_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'2.1\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=2;
	$ab_tbl=1;
}

if (($av_tbl<=2)&&($ab_tbl<2)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD navigation_intern_sortorder INT( 11 ) UNSIGNED NOT NULL AFTER navigation_sortorder, ADD INDEX ( navigation_intern_sortorder )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'2.2\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=2;
	$ab_tbl=2;
}
#============================================================================================================================
# check Tabelle#1 - END
#============================================================================================================================


#============================================================================================================================
# check Tabelle#2
#============================================================================================================================
$table='vis_page';
$QreadData=osW_Database::getInstance()->query('SHOW TABLE STATUS FROM :database_db: LIKE :table:');
$QreadData->bindRaw(':database_db:', vOut('database_db'));
$QreadData->bindValue(':table:', vOut('database_prefix').$table);
$QreadData->execute();
if ($QreadData->numberOfRows()==1) {
	$QreadData->next();
	$avb_tbl=$QreadData->result['Comment'];
} else {
	$avb_tbl='0.0';
}

$avb_tbl=explode('.', $avb_tbl);
if (count($avb_tbl)==1) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=0;
} elseif (count($avb_tbl)==2) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=intval($avb_tbl[1]);
} else {
	$av_tbl=0;
	$ab_tbl=0;
}

if (($av_tbl==0)&&($ab_tbl==0)) {
	$QwriteData=osW_Database::getInstance()->query('
CREATE TABLE :table: (
  page_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  tool_id int(11) unsigned NOT NULL,
  page_name varchar(32) NOT NULL,
  page_description varchar(64) NOT NULL,
  page_ispublic tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (page_id),
  KEY page_ispublic (page_ispublic),
  KEY tool_id (tool_id),
  KEY page_name (page_name)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE page_name page_name VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: COMMENT = \'1.1\'
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=1;
}

if (($av_tbl<=1)&&($ab_tbl<2)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD  page_name_intern VARCHAR( 32 ) NOT NULL AFTER  tool_id
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( page_name_intern )
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
UPDATE :table: SET page_name_intern=page_name, page_name=page_description, page_description=\'\' WHERE 1
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: COMMENT = \'1.2\'
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=2;
}

if (($av_tbl<=1)&&($ab_tbl<3)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE page_name_intern page_name_intern VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE page_description page_description VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: COMMENT = \'1.3\'
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=3;
}

if (($av_tbl<=1)&&($ab_tbl<4)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD page_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( page_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD page_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( page_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD page_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( page_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD page_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( page_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.4\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=4;
}
#============================================================================================================================
# check Tabelle#2 - END
#============================================================================================================================


#============================================================================================================================
# check Tabelle#3
#============================================================================================================================
$table='vis_page_permission';
$QreadData=osW_Database::getInstance()->query('SHOW TABLE STATUS FROM :database_db: LIKE :table:');
$QreadData->bindRaw(':database_db:', vOut('database_db'));
$QreadData->bindValue(':table:', vOut('database_prefix').$table);
$QreadData->execute();
if ($QreadData->numberOfRows()==1) {
	$QreadData->next();
	$avb_tbl=$QreadData->result['Comment'];
} else {
	$avb_tbl='0.0';
}

$avb_tbl=explode('.', $avb_tbl);
if (count($avb_tbl)==1) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=0;
} elseif (count($avb_tbl)==2) {
	$av_tbl=intval($avb_tbl[0]);
	$ab_tbl=intval($avb_tbl[1]);
} else {
	$av_tbl=0;
	$ab_tbl=0;
}

if (($av_tbl==0)&&($ab_tbl==0)) {
	$QwriteData=osW_Database::getInstance()->query('
CREATE TABLE :table: (
  page_id int(11) unsigned NOT NULL,
  tool_id int(11) unsigned NOT NULL,
  permission_flag varchar(16) NOT NULL,
  KEY permission_flag (permission_flag),
  KEY tool_id (tool_id),
  KEY page_id (page_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<4)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD page_permission_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( page_permission_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD page_permission_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( page_permission_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD page_permission_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( page_permission_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD page_permission_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( page_permission_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.4\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=4;
}
#============================================================================================================================
# check Tabelle#3 - END
#============================================================================================================================

?>