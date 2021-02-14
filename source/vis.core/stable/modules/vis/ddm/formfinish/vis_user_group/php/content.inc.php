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

$element='user_group';

$ar_user_group=array();
foreach(osW_VIS_Manager::getInstance()->getGroups(osW_VIS::getInstance()->getToolIdByModule()) as $group_id => $group_name_intern) {
	if ((isset($_POST[$element.'_'.$group_id]))&&($_POST[$element.'_'.$group_id]==1)) {
		$ar_user_group[$group_id]=1;
	} else {
		$ar_user_group[$group_id]=0;
	}
}

switch (osW_Settings::getInstance()->getAction()) {
	case 'doadd':
		$this->setFormDataElement($ddm_group, $element, $ar_user_group);
	
		foreach($ar_user_group as $group_id => $value) {
			if ($value==1) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_user_group: (group_id, tool_id, user_id) VALUES (:group_id:, :tool_id:, :user_id:)');
				$QinsertData->bindTable(':table_vis_user_group:', 'vis_user_group');
				$QinsertData->bindInt(':group_id:', $group_id);
				$QinsertData->bindInt(':tool_id:', osW_VIS::getInstance()->getToolIdByModule());
				$QinsertData->bindInt(':user_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
				$QinsertData->execute();
			}
		}
		break;
	case 'doedit':
		$this->setFormDataElement($ddm_group, $element, $ar_user_group);

		foreach($ar_user_group as $group_id => $value) {
			$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_user_group: WHERE group_id=:group_id: AND tool_id=:tool_id: AND user_id=:user_id:');
			$QdeleteData->bindTable(':table_vis_user_group:', 'vis_user_group');
			$QdeleteData->bindInt(':group_id:', $group_id);
			$QdeleteData->bindInt(':tool_id:', osW_VIS::getInstance()->getToolIdByModule());
			$QdeleteData->bindInt(':user_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
			$QdeleteData->execute();
			if ($value==1) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis_user_group: (group_id, tool_id, user_id) VALUES (:group_id:, :tool_id:, :user_id:)');
				$QinsertData->bindTable(':table_vis_user_group:', 'vis_user_group');
				$QinsertData->bindInt(':group_id:', $group_id);
				$QinsertData->bindInt(':tool_id:', osW_VIS::getInstance()->getToolIdByModule());
				$QinsertData->bindInt(':user_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
				$QinsertData->execute();
			}
		}
		break;
	case 'dodelete':
		$this->setFormDataElement($ddm_group, $element, $ar_permission);
		
		$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis_user_group: WHERE group_id=:group_id: AND tool_id=:tool_id: AND user_id=:user_id:');
		$QdeleteData->bindTable(':table_vis_user_group:', 'vis_user_group');
		$QdeleteData->bindInt(':group_id:', $group_id);
		$QdeleteData->bindInt(':tool_id:', osW_VIS::getInstance()->getToolIdByModule());
		$QdeleteData->bindInt(':user_id:', h()->_catch($this->getGroupOption($ddm_group, 'db_index'), 0, 'gp'));
		$QdeleteData->execute();
		break;
}

?>