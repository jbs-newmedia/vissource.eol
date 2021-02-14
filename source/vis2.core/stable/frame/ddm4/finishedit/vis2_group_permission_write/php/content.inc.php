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

$ar_permission=$this->getEditElementStorage($ddm_group, substr($element, 0, -6));
$ar_permission_do=$this->getDoEditElementStorage($ddm_group, substr($element, 0, -6));

$element_storage='ddm4_store_form_data';
$element_current='vis2_group_permission';

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

$ar_data=array();
$ar_level=array();
$ar_data[0]='-';
foreach (osW_VIS2_Navigation::getInstance()->getNavigation(0, osW_DDM4::getInstance()->getGroupOption($ddm_group, 'navigation_level'), osW_VIS2::getInstance()->getToolId()) as $navigation_element_1) {
	$ar_level[$navigation_element_1['info']['page_name_intern']]=0;
	$ar_data[$navigation_element_1['info']['page_name_intern']]=$navigation_element_1['info']['navigation_title'];
	if ($navigation_element_1['links']!=array()) {
		foreach ($navigation_element_1['links'] as $navigation_element_2) {
			$ar_level[$navigation_element_2['info']['page_name_intern']]=1;
			$ar_data[$navigation_element_2['info']['page_name_intern']]=$navigation_element_1['info']['navigation_title'].' ➥ '.$navigation_element_2['info']['navigation_title'];
			if ($navigation_element_2['links']!=array()) {
				foreach ($navigation_element_2['links'] as $navigation_element_3) {
					$ar_level[$navigation_element_3['info']['page_name_intern']]=2;
					$ar_data[$navigation_element_3['info']['page_name_intern']]=$navigation_element_1['info']['navigation_title'].' ➥ '.$navigation_element_2['info']['navigation_title'].' ➥ '.$navigation_element_3['info']['navigation_title'];
				}
			}
		}
	}
}
if (isset($ar_data['vis_api'])) {
	unset($ar_data['vis_api']);
}

$permission_list=osW_VIS2_Permission::getInstance()->getPermissionTextList(osW_VIS2::getInstance()->getToolId());
foreach ($ar_permission_do as $permission_page => $permissions) {
	foreach ($permissions as $permission => $flag) {
		if (((!isset($ar_permission[$permission_page]))||(!isset($ar_permission[$permission_page][$permission])))||($ar_permission[$permission_page][$permission]!==$flag)) {
			if ($flag==1) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis2_group_permission: (group_id, permission_page, permission_flag, group_permission_create_time, group_permission_create_user_id, group_permission_update_time, group_permission_update_user_id) VALUES (:group_id:, :permission_page:, :permission_flag:, :group_permission_create_time:, :group_permission_create_user_id:, :group_permission_update_time:, :group_permission_update_user_id:)');
				$QinsertData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
				$QinsertData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
				$QinsertData->bindValue(':permission_page:', $permission_page);
				$QinsertData->bindValue(':permission_flag:', $permission);
				$QinsertData->bindInt(':group_permission_create_time:', $vis_time);
				$QinsertData->bindInt(':group_permission_create_user_id:', $vis_user_id);
				$QinsertData->bindInt(':group_permission_update_time:', $vis_time);
				$QinsertData->bindInt(':group_permission_update_user_id:', $vis_user_id);
				$QinsertData->execute();
				if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
					if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
							osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.$ar_data[$permission_page].' : '.$permission_list[$permission], '✔ '.$ar_data[$permission_page].' : '.$permission_list[$permission], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
						}
					} else {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.$ar_data[$permission_page].' : '.$permission_list[$permission], '✔ '.$ar_data[$permission_page].' : '.$permission_list[$permission]);
					}
					osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
				}
			} elseif (((isset($ar_permission[$permission_page]))&&(isset($ar_permission[$permission_page][$permission])))&&($flag==0)) {
				$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page: AND permission_flag=:permission_flag:');
				$QdeleteData->bindTable(':table_vis2_group_permission:', 'vis2_group_permission');
				$QdeleteData->bindInt(':group_id:', $this->getIndexElementStorage($ddm_group));
				$QdeleteData->bindValue(':permission_page:', $permission_page);
				$QdeleteData->bindValue(':permission_flag:', $permission);
				$QdeleteData->execute();
				if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
					if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
							osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.$ar_data[$permission_page].' : '.$permission_list[$permission], '✘ '.$ar_data[$permission_page].' : '.$permission_list[$permission], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
						}
					} else {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.$ar_data[$permission_page].' : '.$permission_list[$permission], '✘ '.$ar_data[$permission_page].' : '.$permission_list[$permission]);
					}
					osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
				}
			}
		}
	}
}

?>