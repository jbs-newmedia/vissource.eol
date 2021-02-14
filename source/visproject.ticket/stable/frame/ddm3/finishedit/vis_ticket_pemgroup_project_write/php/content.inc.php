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

$ar_tool_user=$this->getEditElementStorage($ddm_group, substr($element, 0, -6));
$ar_tool_user_do=$this->getDoEditElementStorage($ddm_group, substr($element, 0, -6));

$vis_time=time();
$vis_user_id=osW_VIS_User::getInstance()->getId();

foreach ($ar_tool_user_do as $tool_id => $flag) {
	if ((!isset($ar_tool_user[$tool_id]))||($ar_tool_user[$tool_id]!==$flag)) {
		if ($flag==1) {
			$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_ticket_pemgroup_project: (group_id, project_id, group_project_create_time, group_project_create_user_id, group_project_update_time, group_project_update_user_id) VALUES (:group_id:, :project_id:, :group_project_create_time:, :group_project_create_user_id:, :group_project_update_time:, :group_project_update_user_id:)');
			$QinsertData->bindTable(':table_vis_ticket_pemgroup_project:', 'vis_ticket_pemgroup_project');
			$QinsertData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
			$QinsertData->bindInt(':project_id:', $tool_id);
			$QinsertData->bindInt(':group_project_create_time:', $vis_time);
			$QinsertData->bindInt(':group_project_create_user_id:', $vis_user_id);
			$QinsertData->bindInt(':group_project_update_time:', $vis_time);
			$QinsertData->bindInt(':group_project_update_user_id:', $vis_user_id);
			$QinsertData->execute();
		} elseif ((isset($ar_tool_user[$tool_id]))&&($flag==0)) {
			$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_ticket_pemgroup_project: WHERE group_id=:group_id: AND project_id=:project_id:');
			$QdeleteData->bindTable(':table_vis_ticket_pemgroup_project:', 'vis_ticket_pemgroup_project');
			$QdeleteData->bindInt(':project_id:', $tool_id);
			$QdeleteData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
			$QdeleteData->execute();
		}
	}
}

?>