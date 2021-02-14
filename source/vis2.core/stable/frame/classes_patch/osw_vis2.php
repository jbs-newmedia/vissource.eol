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
$table='vis2_tool';
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
  tool_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  tool_name varchar(32) NOT NULL,
  tool_module varchar(32) NOT NULL,
  tool_description varchar(128) NOT NULL,
  tool_status tinyint(1) NOT NULL,
  PRIMARY KEY (tool_id),
  KEY tool_status (tool_status),
  KEY tool_name (tool_name),
  KEY tool_module (tool_module)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD tool_hide_logon TINYINT(1) UNSIGNED NOT NULL AFTER tool_status;');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD INDEX (tool_hide_logon);');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD tool_hide_navigation TINYINT(1) UNSIGNED NOT NULL AFTER tool_hide_logon;');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD INDEX (tool_hide_navigation);');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.1\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$av_tbl=1;
	$ab_tbl=1;
}

if (($av_tbl<=1)&&($ab_tbl<2)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: CHANGE tool_status tool_status TINYINT(1) UNSIGNED NOT NULL;');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.2\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$av_tbl=1;
	$ab_tbl=2;
}


if (($av_tbl<=1)&&($ab_tbl<3)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE tool_module tool_name_intern VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: DROP INDEX tool_module
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( tool_name_intern )
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
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE tool_name tool_name VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE tool_name_intern tool_name_intern VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: COMMENT = \'1.4\'
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=4;
}

if (($av_tbl<=1)&&($ab_tbl<5)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD tool_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( tool_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD tool_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( tool_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD tool_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( tool_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD tool_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( tool_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.5\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=5;
}

if (($av_tbl<=1)&&($ab_tbl<6)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD tool_use_mandant TINYINT(1) UNSIGNED NOT NULL AFTER tool_hide_navigation, ADD INDEX (tool_use_mandant);');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD tool_use_mandantswitch TINYINT(1) UNSIGNED NOT NULL AFTER tool_use_mandant, ADD INDEX (tool_use_mandantswitch);');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.6\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=6;
}
#============================================================================================================================
# check Tabelle#1 - END
#============================================================================================================================


#============================================================================================================================
# check Tabelle#2
#============================================================================================================================
$table='vis2_user_tool';
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
	user_id int(11) unsigned NOT NULL,
	tool_id int(11) unsigned NOT NULL,
	KEY user_id (user_id),
	KEY tool_id (tool_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_tool_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_tool_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_tool_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_tool_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_tool_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_tool_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_tool_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_tool_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.1\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=1;
}
#============================================================================================================================
# check Tabelle#2 - END
#============================================================================================================================

?>