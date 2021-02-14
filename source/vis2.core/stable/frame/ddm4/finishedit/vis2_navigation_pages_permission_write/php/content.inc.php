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

$ar_navigation_permission=$this->getEditElementStorage($ddm_group, substr($element, 0, -6));
$ar_navigation_permission_do=$this->getDoEditElementStorage($ddm_group, substr($element, 0, -6));

$vis_time=time();
$vis_user_id=osW_VIS2_User::getInstance()->getId();

$element_storage='ddm4_store_form_data';
$element_current='vis2_navigation_pages_permission';

if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
	$vis_user_id=$this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id');
	$vis_time=$this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time');
} else {
	$vis_time=time();
	$vis_user_id=osW_VIS2_User::getInstance()->getId();
}

if ($this->getFinishElementOption($ddm_group, $element, 'group')!='') {
	$group=$this->getFinishElementOption($ddm_group, $element, 'group');
} else {
	$group=$this->getGroupOption($ddm_group, 'table', 'database');
}

$permission_list=osW_VIS2_Permission::getInstance()->getPermissionTextList(osW_VIS2::getInstance()->getToolId());
foreach ($ar_navigation_permission_do as $permission_flag => $flag) {
	if ((!isset($ar_navigation_permission[$permission_flag]))||($ar_navigation_permission[$permission_flag]!==$flag)) {
		if ($flag==1) {
			$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis2_page_permission: (page_id, tool_id, permission_flag, page_permission_create_time, page_permission_create_user_id, page_permission_update_time, page_permission_update_user_id) VALUES (:page_id:, :tool_id:, :permission_flag:, :page_permission_create_time:, :page_permission_create_user_id:, :page_permission_update_time:, :page_permission_update_user_id:)');
			$QinsertData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
			$QinsertData->bindInt(':page_id:', $this->getIndexElementStorage($ddm_group));
			$QinsertData->bindInt(':tool_id:', osW_VIS2::getInstance()->getToolId());
			$QinsertData->bindValue(':permission_flag:', $permission_flag);
			$QinsertData->bindInt(':page_permission_create_time:', $vis_time);
			$QinsertData->bindInt(':page_permission_create_user_id:', $vis_user_id);
			$QinsertData->bindInt(':page_permission_update_time:', $vis_time);
			$QinsertData->bindInt(':page_permission_update_user_id:', $vis_user_id);
			$QinsertData->execute();
			if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
				if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.$permission_list[$permission_flag], '✔ '.$permission_list[$permission_flag], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.$permission_list[$permission_flag], '✔ '.$permission_list[$permission_flag]);
				}
				osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
			}
		} elseif ((isset($ar_navigation_permission[$permission_flag]))&&($flag==0)) {
			$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_page_permission: WHERE page_id=:page_id: AND tool_id=:tool_id: AND permission_flag=:permission_flag:');
			$QdeleteData->bindTable(':table_vis2_page_permission:', 'vis2_page_permission');
			$QdeleteData->bindInt(':page_id:', $this->getIndexElementStorage($ddm_group));
			$QdeleteData->bindInt(':tool_id:', osW_VIS2::getInstance()->getToolId());
			$QdeleteData->bindValue(':permission_flag:', $permission_flag);
			$QdeleteData->execute();
			if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
				if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
					if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.$permission_list[$permission_flag], '✘ '.$permission_list[$permission_flag], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
					}
				} else {
					osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.$permission_list[$permission_flag], '✘ '.$permission_list[$permission_flag]);
				}
				osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
			}
		}
	}
}

?>