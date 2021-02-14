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
$table='vis_permission';
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
  permission_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  tool_id int(11) unsigned NOT NULL,
  permission_flag varchar(16) NOT NULL,
  permission_title varchar(128) NOT NULL,
  PRIMARY KEY (permission_id),
  KEY tool_id (tool_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD permission_ispublic TINYINT( 1 ) UNSIGNED NOT NULL AFTER permission_title,
ADD INDEX ( permission_ispublic )
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
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD permission_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( permission_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD permission_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( permission_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD permission_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( permission_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD permission_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( permission_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.2\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=2;
}
#============================================================================================================================
# check Tabelle#1 - END
#============================================================================================================================

?>