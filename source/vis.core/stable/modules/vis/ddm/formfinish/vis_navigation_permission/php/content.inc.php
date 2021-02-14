<?php

/*
 * Author: Juergen Schwind
 * Copyright: 2011 Juergen Schwind
 * Link: http://oswframe.com
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 *
 */

$element='navigation_permission';

foreach(osW_VIS_Permission::getInstance()->getPermissionTextList() as $permission_flag => $permission_name) {
	if ((isset($_POST[$element.'_'.$permission_flag]))&&($_POST[$element.'_'.$permission_flag]==1)) {
		$ar_navigation_permission[$permission_flag]=1;
	} else {
		$ar_navigation_permission[$permission_flag]=0;
	}
}

switch (osW_Settings::getInstance()->getAction()) {
	case 'doadd':
	case 'doedit':
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_navigation_permission: WHERE navigation_id=:navigation_id:');
		$QdeleteData->bindTable(':table_vis_navigation_permission:', 'vis_navigation_permission');
		$QdeleteData->bindInt(':navigation_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
		$QdeleteData->execute();

		foreach($ar_navigation_permission as $permission_flag => $permission_status) {
			if ($permission_status==1) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_navigation_permission: (navigation_id, permission_flag) VALUES (:navigation_id:, :permission_flag:)');
				$QinsertData->bindTable(':table_vis_navigation_permission:', 'vis_navigation_permission');
				$QinsertData->bindInt(':navigation_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
				$QinsertData->bindValue(':permission_flag:', $permission_flag);
				$QinsertData->execute();
			}
		}
		break;
	case 'dodelete':
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_navigation_permission: WHERE navigation_id=:navigation_id:');
		$QdeleteData->bindTable(':table_vis_navigation_permission:', 'vis_navigation_permission');
		$QdeleteData->bindInt(':navigation_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
		$QdeleteData->execute();
		break;
}

?>