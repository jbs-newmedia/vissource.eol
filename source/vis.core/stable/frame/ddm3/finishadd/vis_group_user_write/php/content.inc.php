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

$ar_tool_user=$this->getAddElementStorage($ddm_group, substr($element, 0, -6));
$ar_tool_user_do=$this->getDoAddElementStorage($ddm_group, substr($element, 0, -6));

$vis_time=time();
$vis_user_id=osW_VIS_User::getInstance()->getId();

foreach ($ar_tool_user_do as $user_id => $flag) {
	if ((!isset($ar_tool_user[$user_id]))||($ar_tool_user[$user_id]!==$flag)) {
		if ($flag==1) {
			$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_user_group: (group_id, user_id, tool_id, user_group_create_time, user_group_create_user_id, user_group_update_time, user_group_update_user_id) VALUES (:group_id:, :user_id:, :tool_id:, :user_group_create_time:, :user_group_create_user_id:, :user_group_update_time:, :user_group_update_user_id:)');
			$QinsertData->bindTable(':table_vis_user_group:', 'vis_user_group');
			$QinsertData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
			$QinsertData->bindInt(':user_id:', $user_id);
			$QinsertData->bindInt(':tool_id:', osW_VIS::getInstance()->getToolID());
			$QinsertData->bindInt(':user_group_create_time:', $vis_time);
			$QinsertData->bindInt(':user_group_create_user_id:', $vis_user_id);
			$QinsertData->bindInt(':user_group_update_time:', $vis_time);
			$QinsertData->bindInt(':user_group_update_user_id:', $vis_user_id);
			$QinsertData->execute();
		}
	}
}

?>