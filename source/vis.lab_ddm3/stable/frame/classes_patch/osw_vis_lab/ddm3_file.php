<?php

/**
 * @author Juergen Schwind
 * @copyright Copyright (c), Juergen Schwind
 * @package osWFrame
 * @link http://oswframe.com
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 *
 */

#============================================================================================================================
# check Tabelle#1
#============================================================================================================================
$table='vis_lab_ddm3_file';
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
  file_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  file_example varchar(255) NOT NULL,
  file_check varchar(16) NOT NULL,
  navigation_id int(11) unsigned NOT NULL,
  PRIMARY KEY (file_id),
  KEY navigation_id (navigation_id)
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