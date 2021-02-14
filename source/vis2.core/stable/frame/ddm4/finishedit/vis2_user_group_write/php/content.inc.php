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

$element_storage='ddm4_store_form_data';
$element_current='vis2_user_group';
$element_more='vis2_group_user';
$group_more='vis2_group';

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

$tool_groups=osW_VIS2_Manager::getInstance()->getGroups(osW_VIS2::getInstance()->getToolID());
$ar_user=osW_VIS2_Manager::getInstance()->getUsers();
if (isset($ar_tool_user_do[osW_VIS2::getInstance()->getToolID()])) {
	foreach ($ar_tool_user_do[osW_VIS2::getInstance()->getToolID()] as $group_id => $flag) {
		if (((!isset($ar_tool_user[osW_VIS2::getInstance()->getToolID()]))||(!isset($ar_tool_user[osW_VIS2::getInstance()->getToolID()][$group_id])))||($ar_tool_user[osW_VIS2::getInstance()->getToolID()][$group_id]!==$flag)) {
			if ($flag==1) {
				$QinsertData=osW_Database::getInstance()->query('INSERT INTO :table_vis2_user_group: (group_id, tool_id, user_id, user_group_create_time, user_group_create_user_id, user_group_update_time, user_group_update_user_id) VALUES (:group_id:, :tool_id:, :user_id:, :user_group_create_time:, :user_group_create_user_id:, :user_group_update_time:, :user_group_update_user_id:)');
				$QinsertData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
				$QinsertData->bindInt(':group_id:', $group_id);
				$QinsertData->bindInt(':tool_id:', osW_VIS2::getInstance()->getToolID());
				$QinsertData->bindInt(':user_id:', $this->getIndexElementStorage($ddm_group));
				$QinsertData->bindInt(':user_group_create_time:', $vis_time);
				$QinsertData->bindInt(':user_group_create_user_id:', $vis_user_id);
				$QinsertData->bindInt(':user_group_update_time:', $vis_time);
				$QinsertData->bindInt(':user_group_update_user_id:', $vis_user_id);
				$QinsertData->execute();
				if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
					if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
							osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$group_id], '✔ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$group_id], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
						}
					} else {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✘ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$group_id], '✔ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$group_id]);
					}
					osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
				}
				if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
					if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
							osW_DDM4_Log::getInstance()->addValue($group_more, $element_more, '✘ '.$ar_user[$this->getIndexElementStorage($ddm_group)].' (Über Benutzer geändert)', '✔ '.$ar_user[$this->getIndexElementStorage($ddm_group)].' (Über Benutzer geändert)', $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
						}
					} else {
						osW_DDM4_Log::getInstance()->addValue($group_more, $element_more, '✘ '.$ar_user[$this->getIndexElementStorage($ddm_group)].' (Über Benutzer geändert)', '✔ '.$ar_user[$this->getIndexElementStorage($ddm_group)].' (Über Benutzer geändert)');
					}
					osW_DDM4_Log::getInstance()->writeValues($group_more, 'group_id', $group_id);;
				}
			} elseif (((isset($ar_tool_user[osW_VIS2::getInstance()->getToolID()]))&&(isset($ar_tool_user[osW_VIS2::getInstance()->getToolID()][$group_id])))&&($flag==0)) {
				$QdeleteData=osW_Database::getInstance()->query('DELETE FROM :table_vis2_user_group: WHERE group_id=:group_id: AND tool_id=:tool_id: AND user_id=:user_id:');
				$QdeleteData->bindTable(':table_vis2_user_group:', 'vis2_user_group');
				$QdeleteData->bindInt(':group_id:', $group_id);
				$QdeleteData->bindInt(':tool_id:', osW_VIS2::getInstance()->getToolID());
				$QdeleteData->bindInt(':user_id:', $this->getIndexElementStorage($ddm_group));
				$QdeleteData->execute();
				if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
					if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
							osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$group_id], '✘ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$group_id], $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
						}
					} else {
						osW_DDM4_Log::getInstance()->addValue($group, $element_current, '✔ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$group_id], '✘ '.osW_VIS2::getInstance()->getToolName().':'.$tool_groups[$group_id]);
					}
					osW_DDM4_Log::getInstance()->writeValues($group, $this->getGroupOption($ddm_group, 'index', 'database'), $this->getIndexElementStorage($ddm_group));
				}
				if ($this->getGroupOption($ddm_group, 'enable_log')===true) {
					if ($this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix')!='') {
						if (!in_array($element_current, [$this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id', $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'])) {
							osW_DDM4_Log::getInstance()->addValue($group_more, $element_more, '✔ '.$ar_user[$this->getIndexElementStorage($ddm_group)].' (Über Benutzer geändert)', '✘ '.$ar_user[$this->getIndexElementStorage($ddm_group)].' (Über Benutzer geändert)', $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_user_id'), $this->getDoEditElementStorage($ddm_group, $this->getFinishElementOption($ddm_group, $element_storage, 'createupdatestatus_prefix').'update_time'));
						}
					} else {
						osW_DDM4_Log::getInstance()->addValue($group_more, $element_more, '✔ '.$ar_user[$this->getIndexElementStorage($ddm_group)].' (Über Benutzer geändert)', '✘ '.$ar_user[$this->getIndexElementStorage($ddm_group)].' (Über Benutzer geändert)');
					}
					osW_DDM4_Log::getInstance()->writeValues($group_more, 'group_id', $group_id);
				}
			}
		}
	}
}

?>