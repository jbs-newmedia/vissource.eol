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

// vis_group / vis_group_permission
$QgetData=osW_Database::getInstance()->query('SELECT group_id FROM :table_vis_group: WHERE tool_id=:tool_id:');
$QgetData->bindTable(':table_vis_group:', 'vis_group');
$QgetData->bindInt(':tool_id:', $_POST['tool_id']);
$QgetData->execute();
if ($QgetData->numberOfRows()>0) {
	while ($QgetData->next()) {
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_group_permission: WHERE permission_flag=:permission_flag: AND group_id=:group_id:');
		$QdeleteData->bindTable(':table_vis_group_permission:', 'vis_group_permission');
		$QdeleteData->bindValue(':permission_flag:', $this->getDeleteElementStorage($ddm_group, 'permission_flag'));
		$QdeleteData->bindInt(':group_id:', $QgetData->Value('group_id'));
		$QdeleteData->execute();
	}
}

?>