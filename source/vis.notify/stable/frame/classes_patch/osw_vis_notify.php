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
$table='vis_notify';
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
  notify_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  user_id int(11) unsigned NOT NULL,
  notify_message varchar(512) NOT NULL,
  notify_style varchar(16) NOT NULL,
  notify_time int(11) unsigned NOT NULL,
  notify_lifetime int(11) unsigned NOT NULL,
  notify_create_time int(11) unsigned NOT NULL,
  notify_create_user_id int(11) unsigned NOT NULL,
  notify_update_time int(11) unsigned NOT NULL,
  notify_update_user_id int(11) unsigned NOT NULL,
  PRIMARY KEY (notify_id),
  KEY user_id (user_id),
  KEY notify_time (notify_time),
  KEY notify_lifetime (notify_lifetime),
  KEY notify_create_time (notify_create_time),
  KEY notify_create_user_id (notify_create_user_id),
  KEY notify_update_time (notify_update_time),
  KEY notify_update_user_id (notify_update_user_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}
#============================================================================================================================
# check Tabelle#1 - END
#============================================================================================================================

?>