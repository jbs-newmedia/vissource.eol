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


switch (osW_Settings::getInstance()->getAction()) {
	case 'dodelete':
		$ar_groups=array();
		$QgetData=osW_Database::getInstance()->query('SELECT permission_flag FROM :table_vis_permission: WHERE permission_id=:permission_id:');
		$QgetData->bindTable(':table_vis_permission:', 'vis_permission');
		$QgetData->bindInt(':permission_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
		$QgetData->execute();

		if ($QgetData->numberOfRows()==1) {
			$QgetData->next();

			$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_navigation_permission: WHERE permission_flag=:permission_flag:');
			$QdeleteData->bindTable(':table_vis_navigation_permission:', 'vis_navigation_permission');
			$QdeleteData->bindValue(':permission_flag:', $QgetData->result['permission_flag']);
			$QdeleteData->execute();
		}

		break;
}

?>