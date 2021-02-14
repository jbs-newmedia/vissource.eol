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
$table='vis_ticket';
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
  ticket_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  ticket_number bigint(12) unsigned NOT NULL,
  project_id int(11) unsigned NOT NULL,
  group_id int(11) unsigned NOT NULL,
  user_id int(11) unsigned NOT NULL,
  ticket_title varchar(64) NOT NULL,
  ticket_description text NOT NULL,
  importance_id int(11) unsigned NOT NULL,
  ticket_enddate int(8) unsigned NOT NULL,
  ticket_data1 varchar(255) NOT NULL,
  ticket_data1_name varchar(128) NOT NULL,
  ticket_data1_type varchar(32) NOT NULL,
  ticket_data1_size int(11) unsigned NOT NULL,
  ticket_data2 varchar(255) NOT NULL,
  ticket_data2_name varchar(128) NOT NULL,
  ticket_data2_type varchar(32) NOT NULL,
  ticket_data2_size int(11) unsigned NOT NULL,
  ticket_data3 varchar(255) NOT NULL,
  ticket_data3_name varchar(128) NOT NULL,
  ticket_data3_type varchar(32) NOT NULL,
  ticket_data3_size int(11) unsigned NOT NULL,
  ticket_data4 varchar(255) NOT NULL,
  ticket_data4_name varchar(128) NOT NULL,
  ticket_data4_type varchar(32) NOT NULL,
  ticket_data4_size int(11) unsigned NOT NULL,
  ticket_data5 varchar(255) NOT NULL,
  ticket_data5_name varchar(128) NOT NULL,
  ticket_data5_type varchar(32) NOT NULL,
  ticket_data5_size int(11) unsigned NOT NULL,
  ticket_data6 varchar(255) NOT NULL,
  ticket_data6_name varchar(128) NOT NULL,
  ticket_data6_type varchar(32) NOT NULL,
  ticket_data6_size int(11) unsigned NOT NULL,
  ticket_data7 varchar(255) NOT NULL,
  ticket_data7_name varchar(128) NOT NULL,
  ticket_data7_type varchar(32) NOT NULL,
  ticket_data7_size int(11) unsigned NOT NULL,
  ticket_data8 varchar(255) NOT NULL,
  ticket_data8_name varchar(128) NOT NULL,
  ticket_data8_type varchar(32) NOT NULL,
  ticket_data8_size int(11) unsigned NOT NULL,
  ticket_data9 varchar(255) NOT NULL,
  ticket_data9_name varchar(128) NOT NULL,
  ticket_data9_type varchar(32) NOT NULL,
  ticket_data9_size int(11) unsigned NOT NULL,
  ticket_data10 varchar(255) NOT NULL,
  ticket_data10_name varchar(128) NOT NULL,
  ticket_data10_type varchar(32) NOT NULL,
  ticket_data10_size int(11) unsigned NOT NULL,
  status_id int(11) unsigned NOT NULL,
  ticket_create_time int(11) unsigned NOT NULL,
  ticket_create_user_id int(11) unsigned NOT NULL,
  ticket_update_time int(11) unsigned NOT NULL,
  ticket_update_user_id int(11) unsigned NOT NULL,
  PRIMARY KEY (ticket_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD ticket_time_planned int(11) unsigned NOT NULL AFTER ticket_enddate
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( ticket_time_planned )
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD ticket_time_needed int(11) unsigned NOT NULL AFTER ticket_time_planned
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( ticket_time_needed )
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
	for($i=1;$i<=10;$i++) {
		$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD ticket_data'.$i.'_md5 VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER ticket_data'.$i.'_size, ADD INDEX (ticket_data'.$i.'_md5);
		');
		$QwriteData->bindTable(':table:', $table);
		$QwriteData->execute();
	}

	$QreadData=osW_Database::getInstance()->query('SELECT * FROM :table: WHERE 1');
	$QreadData->bindTable(':table:', $table);
	$QreadData->execute();
	if ($QreadData->numberOfRows()>0) {
		while ($QreadData->next()) {
			for($i=1;$i<=10;$i++) {
				if ($QreadData->result['ticket_data'.$i]!='') {
					$QupdateData=osW_Database::getInstance()->query('UPDATE :table: SET ticket_data'.$i.'_md5=:ticket_data'.$i.'_md5: WHERE ticket_id=:ticket_id:');
					$QupdateData->bindTable(':table:', $table);
					$QupdateData->bindValue(':ticket_data'.$i.'_md5:', md5(file_get_contents(root_path.$QreadData->result['ticket_data'.$i])));
					$QupdateData->bindInt(':ticket_id:', $QreadData->result['ticket_id']);
					$QupdateData->execute();
				}
			}
		}
	}

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
ALTER TABLE :table: ADD ticket_notice_count INT(11) NOT NULL AFTER status_id, ADD INDEX (ticket_notice_count)
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();


	$QreadData=osW_Database::getInstance()->query('SELECT * FROM :table: WHERE 1');
	$QreadData->bindTable(':table:', $table);
	$QreadData->execute();
	if ($QreadData->numberOfRows()>0) {
		while ($QreadData->next()) {
			$QreadData2=osW_Database::getInstance()->query('SELECT COUNT(notice_id) AS count FROM :table: WHERE ticket_id=:ticket_id:');
			$QreadData2->bindTable(':table:', 'vis_ticket_notice');
			$QreadData2->bindInt(':ticket_id:', $QreadData->result['ticket_id']);
			$QreadData2->execute();
			$QreadData2->next();

			$QupdateData=osW_Database::getInstance()->query('UPDATE :table: SET ticket_notice_count=:ticket_notice_count: WHERE ticket_id=:ticket_id:');
			$QupdateData->bindTable(':table:', $table);
			$QupdateData->bindInt(':ticket_notice_count:', $QreadData2->result['count']);
			$QupdateData->bindInt(':ticket_id:', $QreadData->result['ticket_id']);
			$QupdateData->execute();
		}
	}

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: COMMENT = \'1.3\'
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=3;
}
#============================================================================================================================
# check Tabelle#1 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#2
#============================================================================================================================
$table='vis_ticket_group';
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
  group_name_intern varchar(32) NOT NULL,
  group_name varchar(32) NOT NULL,
  group_description varchar(64) NOT NULL,
  group_status int(1) unsigned NOT NULL,
  group_isdefault tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (group_id),
  KEY group_status (group_status),
  KEY group_name_intern (group_name_intern)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE group_name_intern group_name_intern VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE group_name group_name VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE group_description group_description VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( group_name )
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( group_isdefault )
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

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.2\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=2;
}
#============================================================================================================================
# check Tabelle#2 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#3
#============================================================================================================================
$table='vis_ticket_group_project';
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
  project_id int(11) unsigned NOT NULL,
  KEY group_id (group_id),
  KEY project_id (project_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_project_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_project_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_project_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_project_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_project_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_project_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD group_project_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( group_project_update_user_id )');
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

#============================================================================================================================
# check Tabelle#4
#============================================================================================================================
$table='vis_ticket_importance';
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
  importance_id int(11) NOT NULL AUTO_INCREMENT,
  importance_internal_id int(11) unsigned NOT NULL,
  importance_description varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (importance_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( importance_internal_id )
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
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD importance_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( importance_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD importance_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( importance_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD importance_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( importance_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD importance_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( importance_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.2\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=2;
}
#============================================================================================================================
# check Tabelle#4 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#5
#============================================================================================================================
$table='vis_ticket_notice';
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
  notice_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  ticket_id int(11) unsigned NOT NULL,
  notice_description text NOT NULL,
  notice_data1 varchar(255) NOT NULL,
  notice_data1_name varchar(128) NOT NULL,
  notice_data1_type varchar(32) NOT NULL,
  notice_data1_size int(11) unsigned NOT NULL,
  notice_data2 varchar(255) NOT NULL,
  notice_data2_name varchar(128) NOT NULL,
  notice_data2_type varchar(32) NOT NULL,
  notice_data2_size int(11) unsigned NOT NULL,
  notice_data3 varchar(255) NOT NULL,
  notice_data3_name varchar(128) NOT NULL,
  notice_data3_type varchar(32) NOT NULL,
  notice_data3_size int(11) unsigned NOT NULL,
  notice_data4 varchar(255) NOT NULL,
  notice_data4_name varchar(128) NOT NULL,
  notice_data4_type varchar(32) NOT NULL,
  notice_data4_size int(11) unsigned NOT NULL,
  notice_data5 varchar(255) NOT NULL,
  notice_data5_name varchar(128) NOT NULL,
  notice_data5_type varchar(32) NOT NULL,
  notice_data5_size int(11) unsigned NOT NULL,
  notice_data6 varchar(255) NOT NULL,
  notice_data6_name varchar(128) NOT NULL,
  notice_data6_type varchar(32) NOT NULL,
  notice_data6_size int(11) unsigned NOT NULL,
  notice_data7 varchar(255) NOT NULL,
  notice_data7_name varchar(128) NOT NULL,
  notice_data7_type varchar(32) NOT NULL,
  notice_data7_size int(11) unsigned NOT NULL,
  notice_data8 varchar(255) NOT NULL,
  notice_data8_name varchar(128) NOT NULL,
  notice_data8_type varchar(32) NOT NULL,
  notice_data8_size int(11) unsigned NOT NULL,
  notice_data9 varchar(255) NOT NULL,
  notice_data9_name varchar(128) NOT NULL,
  notice_data9_type varchar(32) NOT NULL,
  notice_data9_size int(11) unsigned NOT NULL,
  notice_data10 varchar(255) NOT NULL,
  notice_data10_name varchar(128) NOT NULL,
  notice_data10_type varchar(32) NOT NULL,
  notice_data10_size int(11) unsigned NOT NULL,
  notice_create_time int(11) unsigned NOT NULL,
  notice_create_user_id int(11) unsigned NOT NULL,
  notice_update_time int(11) unsigned NOT NULL,
  notice_update_user_id int(11) unsigned NOT NULL,
  PRIMARY KEY (notice_id),
  KEY navigation_id (ticket_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	for($i=1;$i<=10;$i++) {
		$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD notice_data'.$i.'_md5 VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER notice_data'.$i.'_size, ADD INDEX (notice_data'.$i.'_md5);
		');
		$QwriteData->bindTable(':table:', $table);
		$QwriteData->execute();
	}

	$QreadData=osW_Database::getInstance()->query('SELECT * FROM :table: WHERE 1');
	$QreadData->bindTable(':table:', $table);
	$QreadData->execute();
	if ($QreadData->numberOfRows()>0) {
		while ($QreadData->next()) {
			for($i=1;$i<=10;$i++) {
				if ($QreadData->result['notice_data'.$i]!='') {
					$QupdateData=osW_Database::getInstance()->query('UPDATE :table: SET notice_data'.$i.'_md5=:notice_data'.$i.'_md5: WHERE notice_id=:notice_id:');
					$QupdateData->bindTable(':table:', $table);
					$QupdateData->bindValue(':notice_data'.$i.'_md5:', md5(file_get_contents(root_path.$QreadData->result['notice_data'.$i])));
					$QupdateData->bindInt(':notice_id:', $QreadData->result['notice_id']);
					$QupdateData->execute();
				}
			}
		}
	}

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: COMMENT = \'1.1\'
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=1;
}
#============================================================================================================================
# check Tabelle#5 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#6
#============================================================================================================================
$table='vis_ticket_project';
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
  project_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  project_name varchar(32) NOT NULL,
  project_description varchar(64) NOT NULL,
  project_status int(1) unsigned NOT NULL,
  PRIMARY KEY (project_id),
  KEY project_name (project_name),
  KEY project_status (project_status)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD project_parent_id int(11) unsigned NOT NULL AFTER project_id
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( project_parent_id )
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
ALTER TABLE :table: ADD project_sortorder int(11) unsigned NOT NULL AFTER project_status
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( project_sortorder )
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
ALTER TABLE :table: ADD project_name_intern VARCHAR( 32 ) NOT NULL AFTER project_name
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QreadData=osW_Database::getInstance()->query('SELECT * FROM :table: WHERE 1');
	$QreadData->bindTable(':table:', $table);
	$QreadData->execute();
	if ($QreadData->numberOfRows()>0) {
		while ($QreadData->next()) {
			$QupdateData=osW_Database::getInstance()->query('UPDATE :table: SET project_name_intern=:project_name_intern:, project_name=project_description WHERE project_id=:project_id:');
			$QupdateData->bindTable(':table:', $table);
			$QupdateData->bindValue(':project_name_intern:', strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace('-', '_', $QreadData->result['project_name']))));
			$QupdateData->bindInt(':project_id:', $QreadData->result['project_id']);
			$QupdateData->execute();
		}
	}

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
ALTER TABLE :table: CHANGE project_name project_name VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE project_name_intern project_name_intern VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: CHANGE project_description project_description VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( project_name_intern )
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
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD project_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( project_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD project_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( project_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD project_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( project_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD project_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( project_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.5\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=5;
}
#============================================================================================================================
# check Tabelle#6 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#7
#============================================================================================================================
$table='vis_ticket_status';
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
  status_id int(11) unsigned NOT NULL AUTO_INCREMENT,
  status_description varchar(32) CHARACTER SET utf8 NOT NULL,
  status_open tinyint(1) unsigned NOT NULL,
  status_closed tinyint(1) unsigned NOT NULL,
  status_sortorder int(11) unsigned NOT NULL,
  PRIMARY KEY (status_id),
  KEY status_closed (status_closed),
  KEY status_sortorder (status_sortorder)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD status_flag INT( 11 ) UNSIGNED NOT NULL
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: ADD INDEX ( status_flag )
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QreadData=osW_Database::getInstance()->query('SELECT * FROM :table: WHERE 1');
	$QreadData->bindTable(':table:', $table);
	$QreadData->execute();
	if ($QreadData->numberOfRows()>0) {
		while ($QreadData->next()) {
			$flag=0;
			if ($QreadData->result['status_open']==1) {
				$flag=1;
			}
			if ($QreadData->result['status_closed']==1) {
				$flag=9;
			}

			$QupdateData=osW_Database::getInstance()->query('UPDATE :table: SET status_flag=:status_flag: WHERE status_id=:status_id:');
			$QupdateData->bindTable(':table:', $table);
			$QupdateData->bindInt(':status_flag:', $flag);
			$QupdateData->bindInt(':status_id:', $QreadData->result['status_id']);
			$QupdateData->execute();
		}
	}

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: DROP status_open
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
ALTER TABLE :table: DROP status_closed
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
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD status_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( status_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD status_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( status_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD status_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( status_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD status_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( status_update_user_id )');
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
ALTER TABLE :table: ADD status_internal_id int(11) unsigned NOT NULL AFTER status_id
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('
UPDATE :table: SET status_internal_id=status_id WHERE 1
	');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.3\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=3;
}
#============================================================================================================================
# check Tabelle#7 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#8
#============================================================================================================================
$table='vis_ticket_user_group';
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
  KEY user_id (user_id),
  KEY group_id (group_id)
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
# check Tabelle#8 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#9
#============================================================================================================================
$table='vis_ticket_user_project';
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
  project_id int(11) unsigned NOT NULL,
  KEY project_id (project_id),
  KEY user_id (user_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}

if (($av_tbl<=1)&&($ab_tbl<1)) {
	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_project_create_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_project_create_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_project_create_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_project_create_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_project_update_time INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_project_update_time )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: ADD user_project_update_user_id INT( 11 ) UNSIGNED NOT NULL, ADD INDEX ( user_project_update_user_id )');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();

	$QwriteData=osW_Database::getInstance()->query('ALTER TABLE :table: COMMENT = \'1.1\';');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=1;
}
#============================================================================================================================
# check Tabelle#9 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#10
#============================================================================================================================
$table='vis_ticket_pemgroup';
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
  group_name_intern varchar(64) NOT NULL,
  group_name varchar(64) NOT NULL,
  group_description varchar(128) NOT NULL,
  group_status int(1) unsigned NOT NULL,
  group_isdefault tinyint(1) unsigned NOT NULL,
  group_create_time int(11) unsigned NOT NULL,
  group_create_user_id int(11) unsigned NOT NULL,
  group_update_time int(11) unsigned NOT NULL,
  group_update_user_id int(11) unsigned NOT NULL,
  PRIMARY KEY (group_id),
  KEY group_status (group_status),
  KEY group_name_intern (group_name_intern),
  KEY group_name (group_name),
  KEY group_isdefault (group_isdefault),
  KEY group_create_time (group_create_time),
  KEY group_create_user_id (group_create_user_id),
  KEY group_update_time (group_update_time),
  KEY group_update_user_id (group_update_user_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}
#============================================================================================================================
# check Tabelle#10 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#11
#============================================================================================================================
$table='vis_ticket_pemgroup_project';
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
  project_id int(11) unsigned NOT NULL,
  group_project_create_time int(11) unsigned NOT NULL,
  group_project_create_user_id int(11) unsigned NOT NULL,
  group_project_update_time int(11) unsigned NOT NULL,
  group_project_update_user_id int(11) unsigned NOT NULL,
  KEY group_id (group_id),
  KEY project_id (project_id),
  KEY group_project_create_time (group_project_create_time),
  KEY group_project_create_user_id (group_project_create_user_id),
  KEY group_project_update_time (group_project_update_time),
  KEY group_project_update_user_id (group_project_update_user_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}
#============================================================================================================================
# check Tabelle#11 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#12
#============================================================================================================================
$table='vis_ticket_user_pemgroup';
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
  user_group_create_time int(11) unsigned NOT NULL,
  user_group_create_user_id int(11) unsigned NOT NULL,
  user_group_update_time int(11) unsigned NOT NULL,
  user_group_update_user_id int(11) unsigned NOT NULL,
  KEY user_id (user_id),
  KEY group_id (group_id),
  KEY user_group_create_time (user_group_create_time),
  KEY user_group_create_user_id (user_group_create_user_id),
  KEY user_group_update_time (user_group_update_time),
  KEY user_group_update_user_id (user_group_update_user_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}
#============================================================================================================================
# check Tabelle#12 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#13
#============================================================================================================================
$table='vis_ticket_notify';
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
  project_id int(11) unsigned NOT NULL,
  notify_sub tinyint(1) unsigned NOT NULL,
  user_id int(11) unsigned NOT NULL,
  notiz_create_user_id int(11) unsigned NOT NULL,
  notiz_create_time int(11) unsigned NOT NULL,
  notiz_update_user_id int(11) unsigned NOT NULL,
  notiz_update_time int(11) unsigned NOT NULL,
KEY project_id (project_id),
KEY notify_sub (notify_sub)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}
#============================================================================================================================
# check Tabelle#13 - END
#============================================================================================================================

#============================================================================================================================
# check Tabelle#14
#============================================================================================================================
$table='vis_ticket_notify_ticket';
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
  ticket_id int(11) unsigned NOT NULL,
  user_id int(11) unsigned NOT NULL,
  notiz_create_user_id int(11) unsigned NOT NULL,
  notiz_create_time int(11) unsigned NOT NULL,
  notiz_update_user_id int(11) unsigned NOT NULL,
  notiz_update_time int(11) unsigned NOT NULL,
KEY ticket_id (ticket_id)
) ENGINE='.vOut('database_engine').' DEFAULT CHARSET=utf8 COMMENT=\'1.0\';
');
	$QwriteData->bindTable(':table:', $table);
	$QwriteData->execute();
	$av_tbl=1;
	$ab_tbl=0;
}
#============================================================================================================================
# check Tabelle#14 - END
#============================================================================================================================

?>