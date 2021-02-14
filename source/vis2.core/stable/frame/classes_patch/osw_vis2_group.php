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
$table='vis2_group';
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
  group_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  group_name varchar(64) NOT NULL,
  group_status int(11) unsigned NOT NULL,
  tool_id int(11) unsigned NOT NULL,
  PRIMARY KEY (group_id),
  KEY tool_id (tool_id),
  KEY group_status (group_status)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD group_description VARCHAR( 32 ) NOT NULL AFTER group_name
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QreadData=osW_Database::getInstance()->query('SELECT * FROM :table: WHERE 1');
	$QreadData->bindTable(':table:', $table);
	$QreadData->execute();
	if ($QreadData->numberOfRows()>0) {
		while ($QreadData->next()) {
			$QupdateData=osW_Database::getInstance()->query('UPDATE :table: SET group_description=group_name, group_name=:group_name: WHERE group_id=:group_id:');
			$QupdateData->bindTable(':table:', $table);
			$QupdateData->bindValue(':group_name:', strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace('-', '_', $QreadData->result['group_name']))));
			$QupdateData->bindInt(':group_id:', $QreadData->result['group_id']);
			$QupdateData->execute();
		}
	}

/*
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE group_name_intern group_name_intern VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
*/

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( group_name )
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

/*
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE group_name_intern group_name_intern VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
*/

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
ALTER TABLE :table: ADD group_name_intern VARCHAR( 32 ) NOT NULL AFTER group_id
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( group_name_intern )
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE group_description group_description VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
UPDATE :table: SET group_name_intern=group_name, group_name=group_description, group_description=\'\' WHERE 1
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
ALTER TABLE :table: CHANGE group_name_intern group_name_intern VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE group_description group_description VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
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
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.5\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=5;
}

if (($av_tbl<=1)&&($ab_tbl<6)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: CHANGE group_status group_status TINYINT(1) UNSIGNED NOT NULL');
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
$table='vis2_user_group';
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
  group_id int(11) unsigned NOT NULL,
  tool_id int(11) unsigned NOT NULL,
  KEY user_id (user_id),
  KEY group_id (group_id),
  KEY tool_id (tool_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_group_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_group_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_group_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_group_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_group_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_group_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_group_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_group_update_user_id )');
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


#============================================================================================================================
# check Tabelle#3
#============================================================================================================================
$table='vis2_group_permission';
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
  group_id int(11) unsigned NOT NULL,
  permission_page varchar(64) NOT NULL,
  permission_flag varchar(16) NOT NULL,
  KEY group_id (group_id),
  KEY permission_page (permission_page),
  KEY permission_flag (permission_flag)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_permission_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_permission_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_permission_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_permission_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_permission_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_permission_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_permission_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_permission_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.1\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=1;
}
#============================================================================================================================
# check Tabelle#3 - END
#============================================================================================================================

?>