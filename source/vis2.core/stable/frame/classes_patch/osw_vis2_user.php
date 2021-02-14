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
$table='vis2_user';
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
  user_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  user_email varchar(64) NOT NULL,
  user_name varchar(32) NOT NULL,
  user_password varchar(35) NOT NULL,
  user_status tinyint(1) unsigned NOT NULL,
  user_token varchar(32) NOT NULL,
  PRIMARY KEY (user_id),
  KEY user_status (user_status),
  KEY user_cookie (user_token),
  KEY user_email (user_email)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_form VARCHAR( 16 ) NOT NULL AFTER user_name');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_firstname VARCHAR( 32 ) NOT NULL AFTER user_form');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_lastname VARCHAR( 32 ) NOT NULL AFTER user_firstname');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD INDEX (user_firstname)');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD INDEX (user_lastname)');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.1\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$av_tbl=1;
	$ab_tbl=1;
}

if (($av_tbl<=1)&&($ab_tbl<2)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_phone VARCHAR( 32 ) NOT NULL AFTER user_lastname');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_fax VARCHAR( 32 ) NOT NULL AFTER user_phone');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_mobile VARCHAR( 32 ) NOT NULL AFTER user_fax');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD INDEX (user_phone)');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD INDEX (user_fax)');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD INDEX (user_mobile)');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.2\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$av_tbl=1;
	$ab_tbl=2;
}

if (($av_tbl<=1)&&($ab_tbl<3)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_gender int(11) unsigned NOT NULL AFTER user_lastname');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD INDEX (user_gender)');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.3\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=3;
}

if (($av_tbl<=1)&&($ab_tbl<4)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: DROP INDEX user_cookie');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD INDEX (user_token)');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.4\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=4;
}

if (($av_tbl<=1)&&($ab_tbl<5)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.5\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=5;
}
#============================================================================================================================
# check Tabelle#1 - END
#============================================================================================================================


#============================================================================================================================
# check Tabelle#2
#============================================================================================================================
$table='vis2_user_pref';
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
  pref_name varchar(32) NOT NULL,
  pref_type varchar(12) NOT NULL,
  pref_create int(11) unsigned NOT NULL,
  pref_lastupdate int(11) unsigned NOT NULL,
  pref_integer int(11) NOT NULL,
  pref_float float NOT NULL,
  pref_string varchar(255) NOT NULL,
  pref_text longtext NOT NULL,
  pref_date date NOT NULL,
  UNIQUE KEY userid_pref_name (user_id,pref_name),
  KEY user_id (user_id),
  KEY pref_name (pref_name)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE pref_name pref_name VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
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
#============================================================================================================================
# check Tabelle#2 - END
#============================================================================================================================

?>