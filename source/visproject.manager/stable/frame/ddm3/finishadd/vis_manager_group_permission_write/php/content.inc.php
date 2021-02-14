<?php

/*
 * Author: Juergen Schwind
 * Copyright: Juergen Schwind
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

$ar_permission=$this->getAddElementStorage($ddm_group, substr($element, 0, -6));
$ar_permission_do=$this->getDoAddElementStorage($ddm_group, substr($element, 0, -6));

$vis_time=time();
$vis_user_id=osW_VIS_User::getInstance()->getId();

foreach ($ar_permission_do as $permission_page => $permissions) {
	foreach ($permissions as $permission => $flag) {
		if (((!isset($ar_permission[$permission_page]))||(!isset($ar_permission[$permission_page][$permission])))||($ar_permission[$permission_page][$permission]!==$flag)) {
			if ($flag==1) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_group_permission: (group_id, permission_page, permission_flag, group_permission_create_time, group_permission_create_user_id, group_permission_update_time, group_permission_update_user_id) VALUES (:group_id:, :permission_page:, :permission_flag:, :group_permission_create_time:, :group_permission_create_user_id:, :group_permission_update_time:, :group_permission_update_user_id:)');
				$QinsertData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
				$QinsertData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
				$QinsertData->bindValue(':permission_page:', $permission_page);
				$QinsertData->bindValue(':permission_flag:', $permission);
				$QinsertData->bindInt(':group_permission_create_time:', $vis_time);
				$QinsertData->bindInt(':group_permission_create_user_id:', $vis_user_id);
				$QinsertData->bindInt(':group_permission_update_time:', $vis_time);
				$QinsertData->bindInt(':group_permission_update_user_id:', $vis_user_id);
				$QinsertData->execute();
			}
		}
	}
}

?>